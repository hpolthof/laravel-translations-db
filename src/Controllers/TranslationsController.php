<?php namespace Hpolthof\Translation\Controllers;

use Hpolthof\Translation\TranslationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Stichoza\GoogleTranslate\TranslateClient;

class TranslationsController extends Controller {

    public function __construct() {
        // Disable the Laravel Debugbar
        $app = app();
        if($app->offsetExists('debugbar') && $app['config']->get('translation-db.disable_debugbar')) {
            $app['debugbar']->disable();
        }
    }

    public function getIndex() {
        return view('translation::index');
    }

    public function getGroups() {
        return \DB::table('translations')
            ->select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');
    }

    public function getLocales() {
        return \DB::table('translations')
            ->select('locale')
            ->distinct()
            ->orderBy('locale')
            ->pluck('locale');
    }

    public function postItems(Request $request) {
        if(strlen($request->get('translate')) == 0) throw new TranslationException();

        $base = \DB::table('translations')
            ->select('name', 'value')
            ->where('locale', $request->get('locale'))
            ->where('group', $request->get('group'))
            ->orderBy('name')
            ->get();
        $new = \DB::table('translations')
            ->select('name', 'value')
            ->where('locale', strtolower($request->get('translate')))
            ->where('group', $request->get('group'))
            ->orderBy('name')
            ->pluck('value', 'name');

        foreach($base as &$item) {
            $translate = null;

            if(array_key_exists($item->name, $new)) {
                $translate = $new[$item->name];
            }
            $item->translation = $translate;
        }

        return $base;
    }

    public function postStore(Request $request) {
        $item = \DB::table('translations')
            ->where('locale', strtolower($request->get('locale')))
            ->where('group', $request->get('group'))
            ->where('name', $request->get('name'))->first();

        $data = [
            'locale' => strtolower($request->get('locale')),
            'group' => $request->get('group'),
            'name' => $request->get('name'),
            'value' => $request->get('value'),
            'updated_at' => date_create(),
        ];

        if($item === null) {
            $data = array_merge($data, [
                'created_at' => date_create(),
            ]);
            $result = \DB::table('translations')->insert($data);
        } else {
            $result = \DB::table('translations')->where('id', $item->id)->update($data);
        }

        if(!$result) {
            throw new TranslationException('Database error...');
        }
        return 'OK';
    }

    public function postTranslate(Request $request) {
        $text = TranslateClient::translate($request->input('origin'), $request->input('target'), $request->input('text'));
        $key = $request->input('key');
        return compact('key', 'text');
    }

    public function postDelete(Request $request)
    {
        \DB::table('translations')
            ->where('name', strtolower($request->get('name')))->delete();
        return 'OK';
    }
}