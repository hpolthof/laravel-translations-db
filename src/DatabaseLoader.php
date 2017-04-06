<?php namespace Hpolthof\Translation;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Translation\LoaderInterface;

class DatabaseLoader implements LoaderInterface {

    protected $_app = null;

    public function __construct(Application $app)
    {
        $this->_app = $app;
    }

    /**
     * Load the messages for the given locale.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        $query = \DB::table('translations')
            ->where('locale', $locale)
            ->where('group', $group);

        return ServiceProvider::pluckOrLists($query, 'value', 'name');
    }

    /**
     * Add a new namespace to the loader.
     * This function will not be used but is required
     * due to the LoaderInterface.
     * We'll just leave it here as is.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */
    public function addNamespace($namespace, $hint) {}

    /**
     * Adds a new translation to the database or
     * updates an existing record if the viewed_at
     * updates are allowed.
     *
     * @param string $locale
     * @param string $group
     * @param string $name
     * @return void
     */
    public function addTranslation($locale, $group, $key)
    {
        if(!\Config::get('app.debug') || \Config::get('translation-db.minimal')) return;

        // Extract the real key from the translation.
        if (preg_match("/^{$group}\.(.*?)$/sm", $key, $match)) {
            $name = $match[1];
        } else {
            throw new TranslationException('Could not extract key from translation.');
        }

        $item = \DB::table('translations')
            ->where('locale', $locale)
            ->where('group', $group)
            ->where('name', $name)->first();

        $data = compact('locale', 'group', 'name');
        $data = array_merge($data, [
            'viewed_at' => date_create(),
            'updated_at' => date_create(),
        ]);

        if($item === null) {
            $data = array_merge($data, [
                'created_at' => date_create(),
            ]);
            \DB::table('translations')->insert($data);
        } else {
            if($this->_app['config']->get('translation-db.update_viewed_at')) {
                \DB::table('translations')->where('id', $item->id)->update($data);
            }
        }
    }

    /** Laravel 5.4  additions **/

     /**
     * Get an array of all the registered namespaces.
     * This function will not be used but is required
     * due to the LoaderInterface.
     * We'll just leave it here as is.
     *     
     * @return void
     */
    public function namespaces() {}

}
