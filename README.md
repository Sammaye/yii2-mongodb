# MongoYii2

A set of tools and addons bult on top of Yii2's own MongoDB extension to further it's capacity and abilities 
because I found the default MongoDB extension lacked a lot of features needed in programs I made in MongoDB.

## Features

### Formatter

The formatter, which replaces Yii2's own, provides some standard representations of objects, especially `MongoDate`s.

To use it simple add: 

	'formatter' => ['class' => 'sammaye\mongoyii2\Formatter']
	
to your `components` section in your configuration file, for example, in `common/config/main.php`.

After you have added it you can define certain fields as certain data types. Let's take an example of defining a `GridView`'s `columns` property:

	'columns' => [
		'_id',
		'url',
		[
			'attribute' => 'date',
			'format' => 'date'
		],
		'inc_id',
		[
			'attribute' => 'updated_at',
			'format' => 'date'
		],
		[
			'attribute' => 'created_at',
			'format' => 'date'
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'template' => '{update} {delete}',
			'urlCreator' => function($action, $model, $key, $index){
				$params = is_array($key) ? $key : ['id' => (string) $key];
				$params[0] = 'comic-strip' . '/' . $action;
				return Url::toRoute($params);
			}
		]
	]
	
The `date` formats will use the formatter class you included for this extension to ensure that the correct output is used.

### Validators

The most common way to inclue the validators is to include `sammaye\mongoyii2\ActiveRecord` instead of `yii\mongodb\ActiveRecord` within your models.

You an also use all validators directly by calling the class, for example: 

	new sammaye\mongoyii2\NumberValidator()
	
or:

	['field', 'sammaye\mongoyii2\NumberValidator'],

#### Validator Map

MongoYii2 contains it's own validator map when you include the `ActiveRecord`. This currently what it looks like:

	public static $builtInValidators = [
		'id' => 'sammaye\mongoyii2\validators\MongoIdValidator',
		'boolean' => 'yii\validators\BooleanValidator',
		'captcha' => 'yii\captcha\CaptchaValidator',
		'compare' => 'yii\validators\CompareValidator',
		'date' => 'sammaye\mongoyii2\validators\DateValidator',
		'default' => 'yii\validators\DefaultValueValidator',
		'double' => 'yii\validators\NumberValidator',
		'email' => 'yii\validators\EmailValidator',
		'exist' => 'yii\validators\ExistValidator',
		'file' => 'yii\validators\FileValidator',
		'filter' => 'yii\validators\FilterValidator',
		'image' => 'yii\validators\ImageValidator',
		'in' => 'sammaye\mongoyii2\validators\RangeValidator',
		'inInt' => [
			'class' => 'sammaye\mongoyii2\validators\RangeValidator',
			'format' => 'int'
		],
		'integer' => [
			'class' => 'sammaye\mongoyii2\validators\NumberValidator',
			'integerOnly' => true,
			'format' => 'int'
		],
		'float' => [
			'class' => 'sammaye\mongoyii2\validators\NumberValidator',
			'integerOnly' => true,
			'format' => 'float'
		],
		'array' => 'sammaye\mongoyii2\validators\ArrayValidator',
		'match' => 'yii\validators\RegularExpressionValidator',
		'number' => 'sammaye\mongoyii2\validators\NumberValidator',
		'required' => 'yii\validators\RequiredValidator',
		'safe' => 'yii\validators\SafeValidator',
		'string' => 'yii\validators\StringValidator',
		'trim' => [
			'class' => 'yii\validators\FilterValidator',
			'filter' => 'trim',
			'skipOnArray' => true,
		],
		'unique' => 'yii\validators\UniqueValidator',
		'url' => 'yii\validators\UrlValidator',
	];

#### Array Validator

This is designed to allow you to validate very basic subdocuments, simple 1D arrays. You can use it like:

	[
		'array_field',
		'array',
		'max' => 10,
		'rules' =>
			[
				['$', 'intInt', 'range' => array_keys($this->rangeVals())],
			]
	],
	
Essentially there are 4 properties you need to know about for this validator:

- `min` which defines a minimum number of elements within the array
- `max` which does the opposite
- `required` which defines a required field
- `rules` which define the rules for the array.

All defined rules have the same structure:

	['$', rule_name, params],
	
The `$` is always used as denote all elements of the array, currently you cannot target elements directly, for example:

	[1, rule_name, params],
	
This validator will currently batch all errors for the field into a single or set of errors for the field.

#### MongoDate Validator

Does exactly the same as Yii2's own `DateValidator` except it can also cast to a `MongoDate` object if the `cast` property is `true`, which it is by default.

#### MongoId Validator

This checks to see if the value is a MongoId and try and cast it if you have not set the `cast` property to `false`.

#### NumberValidator

Checks if value is a number and will format it if you fill in `format` property. It can take an anon function for the `format` property or `int`, `float`, `string` or nothing. 
Nothing will result in nothing being done to the value. Example model `rules` entry:

	['int_field', 'Number', 'integerOnly' => true, 'format' => 'int']
	
This validator can also be called by:

- integer
- float

These are used as shortcuts for calling the `NumberValidator` with `format` filled in as either `int` or `float`.

#### RangeValidator

Checks if all values is are in a range of other values and will format it the range if you fill in `format` property. 
It can take an anon function for the `format` property or `int`, `float`, `string` or nothing. Nothing will result in nothing being done to the value. 

This validator can also be called by `inInt` which is a shortcut for calling `['intRange', 'in', 'range' => [1,2,3], 'format' => 'int']`.

### Active Query Changes

The active query now has the ability to not only stream, via `each()` but also get the raw cursor back:

	User::find()->where(['cheese' => 'cheddar'])->raw()->info(); // gets cursor info
	
`each()` will get the cursor and one by one instantiate it and use the cursors own batch methods to pull from MongoDB in your configured `batchSize()` 
(normally 100 rows at a time) instead of using the `all()` or nothing method.

This is especially helpful for large scripts where you could easily run out of memory.

### Indexer

The indexer allows you to add indexes to MongoDB from your models.

Currently it will not manage the indexes for you, dropping of indexes is either all or none in the script and you need to use the `mongo` console to do it individually.

To use it simply copy it to your console controller folder, whether it be `console/controllers` or `commands` and edit the first line of the file to change it's namespace and 
then simply run it.

It will tell you what it is doing and what indexes it adds. It will asearch through your models folder and search for any models with the `indexes()` function. Upon reading that 
function's return it will make indexes for that model.

A good example is:

	public function indexes()
	{
		return [
			// db.c.ensureIndex({email: 1,status: 1})
			['email' => 1, 'status' => 1],
			
			// db.c.ensureIndex({email: 1}, {unique: true, sparse: true})
			[['email' => 1], ['unique' => true, 'sparse' => true]],
			
			// db.c.ensureIndex({username: 1}, {unique: true, sparse: true})
			[['username' => 1], ['unique' => true, 'sparse' => true]],
			
			// db.c.ensureIndex({status: 1})
			['status' => 1],
			
			// db.c.ensureIndex({password_reset_token: 1,status: 1})
			['password_reset_token' => 1, 'status' => 1],
			
			// db.c.ensureIndex({_id: 1,status: 1})
			['_id' => 1, 'status' => 1],
			
			// db.c.ensureIndex({facebook_id: 1})
			['facebook_id' => -1],
			
			// db.c.ensureIndex({google_id: 1})
			['google_id' => -1],
		];
	}

### Mongo Helper

Contains some helpful functions which I just had to share:

- Geo coordinates function so you can get distances within your PHP code too
- MongoDB `DATE()` function to get dates from `MongoDate` objects

## What's Still Not Working?

Object subdocuments do not work still. So a document of the structure:

	{
		somefield: {
			{d: 1, e: 2},
			...
		}
	}

will not work automatically with this extension. 

I thought about it long and hard but everything I came up with something I kept wanting to ue it differently for each scenario and case I had object subdocuments.

In the end I just ditched what I had and moved on. I added some thoughts on this [thread if someone wants to take it up](https://github.com/yiisoft/yii2/issues/4899).

Apart from that most of this extension is about making stuff work.