    $ composer create-project --prefer-dist laravel/laravel laravel_test_api
> - dodat u hosts (api.laravel.test)
> - dodat u httpd-vhosts.conf (api.laravel.test)
> - napravit bazu (laravel_api)

	$ cd laravel_test_api
	$ code .
> .editorconfig

	¸¸
	indent_size = 2
	¸¸
> .env

	¸¸
	DB_PORT=3307
	DB_DATABASE=laravel_api
	DB_USERNAME=root
	DB_PASSWORD=
	¸¸
> - git

	$ git init
	$ git add .
	$ git commit -m "Initial Commit - Laravel Framework Installed"
	$ git remote add origin https://github.com/ZdravekSprite/api.laravel.test.git
	$ git push -u origin master
	$ git branch test2
	$ git checkout test2
	$ git push --set-upstream origin test2
	$ php artisan make:model -a Article
> - git

	$ git add .
	$ git commit -m "Generate a migration, factory, and resource controller for the Article"
	$ git push
> database\migrations\2019_10_05_110425_create_articles_table.php

	¸¸
	  public function up()
	  {
	    Schema::create('articles', function (Blueprint $table) {
	      $table->bigIncrements('id');
	      $table->string('title');
	      $table->text('body');
	      $table->timestamps();
	    });
	  }
	¸¸
> - to avoid errors with migration we need to change engine in database congi file from null to InnoDB

> config\database.php

	¸¸
	'mysql' => [
	¸¸
	'engine' => 'InnoDB',
	¸¸
>

	$ php artisan migrate:fresh
> - git

	$ git add .
	$ git commit -m "edit migration"
	$ git push
> database\factories\ArticleFactory.php

	¸¸
	$factory->define(Article::class, function (Faker $faker) {
	  return [
	    'title' => $faker->text(50),
	    'body'  => $faker->text(200)
	  ];
	});
	¸¸
> - git

	$ git add .
	$ git commit -m "edit factory"
	$ git push
	$ php artisan make:seeder ArticlesTableSeeder
> database\seeds\ArticlesTableSeeder.php

	¸¸
	  public function run()
	  {
	    factory(App\Article::class, 30)->create();
	  }
	¸¸
#
	$ composer dump-autoload
	$ php artisan db:seed --class=ArticlesTableSeeder
> - git

	$ git add .
	$ git commit -m "Create a ArticlesTableSeeder seeder class"
	$ git push
	$ php artisan make:resource Article
> app\Http\Resources\Article.php

	¸¸
	  public function toArray($request)
	  {
	    // return parent::toArray($request);
	    return [
	      'id' => $this->id,
	      'title' => $this->title,
	      'body' => $this->body
	    ];
	  }
	  public function with($request)
	  {
	    return [
	      'app_name' => env('APP_NAME'),
	      'app_url' => url('http://api.laravel.test')
	    ];
	  }
	¸¸
> - git

	$ git add .
	$ git commit -m "Create a Article resoutce class"
	$ git push
> routes\api.php

	¸¸
	Route::get('articles', 'ArticleController@index'); //index
	Route::get('article/{id}', 'ArticleController@show'); //show
	Route::post('article', 'ArticleController@store'); //create
	Route::put('article', 'ArticleController@store'); //update
	Route::delete('article/{id}', 'ArticleController@destroy'); //delete
	¸¸
> - git

	$ git add .
	$ git commit -m "Adding routs"
	$ git push
> app\Http\Controllers\ArticleController.php

	¸¸
	use App\Http\Resources\Article as ArticleResource;
	¸¸
	  public function index()
	  {
	    $articles = Article::paginate(15);
	    return ArticleResource::collection($articles);
	  }
	¸¸
	  public function store(Request $request)
	  {
	    $article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;
	
	    $article->id = $request->input('article_id');
	    $article->title = $request->input('title');
	    $article->body = $request->input('body');
	
	    if($article->save()) {
	      return new ArticleResource($article);
	    }
	  }
	¸¸
	  public function show(Article $article)
	  {
	    return new ArticleResource($article);
	  }
	¸¸
	  public function destroy(Article $article)
	  {
	    if($article->delete()) {
	      return new ArticleResource($article);
	    }
	  }
	¸¸
> - git

	$ git add .
	$ git commit -m "edit ArticleController"
	$ git push
> app\Providers\AppServiceProvider.php

	¸¸
	use Illuminate\Http\Resources\Json\Resource;
	¸¸
	  public function boot()
	  {
	    Resource::withoutWrapping();
	  }
	¸¸
> - git

	$ git add .
	$ git commit -m "avoid data object wrapper"
	$ git push
	$ git add .
	$ git commit -m "edit readme"
	$ git push
