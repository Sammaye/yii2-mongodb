# MongoYii2

A set of tools and addons bult on top of Yii2's own MongoDB extension to further it's capacity and abilities.

## Why?

Because I found the default MongoDB extension lacked a lot of features needed in programs I made in MongoDB.

**Again:** This is not designed to replace the Yii2 MongoDB but instead compliment it.

## Features

### Formatter

### Validators

#### Validator Map

#### Array Validator

#### MongoDate Validator

#### MongoId Validator

#### NumberValidator

#### RangeValidator

### Active Query Changes

### Indexer

### Mongo Helper

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