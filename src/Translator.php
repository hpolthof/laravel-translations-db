<?php namespace Hpolthof\Translation;;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Translation\LoaderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Translator extends \Illuminate\Translation\Translator implements TranslatorInterface {

	protected $app = null;

	public function __construct(LoaderInterface $database, LoaderInterface $loader, $locale, Application $app)
	{
		$this->database = $database;
		$this->app = $app;
		parent::__construct($loader, $locale);
	}

	protected static function isNamespaced($namespace)
	{
		return !(is_null($namespace) || $namespace == '*');
	}

	/**
	 * Get the translation for the given key.
	 *
	 * @param  string  $key
	 * @param  array   $replace
	 * @param  string  $locale
	 * @param  bool	   $fallback
	 * @return string
	 */
	public function get($key, array $replace = array(), $locale = null, $fallback = true)
	{
		list($namespace, $group, $item) = $this->parseKey($key);

		// Here we will get the locale that should be used for the language line. If one
		// was not passed, we will use the default locales which was given to us when
		// the translator was instantiated. Then, we can load the lines and return.
		foreach ($this->parseLocale($locale) as $locale)
		{
			if(!self::isNamespaced($namespace)) {
				// Database stuff
				$this->database->addTranslation($locale, $group, $key);
			}

			$this->load($namespace, $group, $locale);

			$line = $this->getLine(
				$namespace, $group, $locale, $item, $replace
			);

			if ( ! is_null($line)) break;
		}

		// If the line doesn't exist, we will return back the key which was requested as
		// that will be quick to spot in the UI if language keys are wrong or missing
		// from the application's language files. Otherwise we can return the line.
		if ( ! isset($line)) return $key;

		return $line;
	}

	public function load($namespace, $group, $locale)
	{
		if ($this->isLoaded($namespace, $group, $locale)) return;

		// If a Namespace is give the Filesystem will be used
		// otherwise we'll use our database.
		// This will allow legacy support.
		if(!self::isNamespaced($namespace)) {
			// If debug is off then cache the result forever to ensure high performance.
			if(!\Config::get('app.debug') || \Config::get('translation-db.minimal')) {
				$that = $this;
				$lines = \Cache::rememberForever('__translations.'.$locale.'.'.$group, function() use ($that, $locale, $group, $namespace) {
					return $this->database->load($locale, $group, $namespace);
				});
			} else {
				$lines = $this->database->load($locale, $group, $namespace);
			}
		} else {
			$lines = $this->loader->load($locale, $group, $namespace);
		}
		$this->loaded[$namespace][$group][$locale] = $lines;
	}
}
