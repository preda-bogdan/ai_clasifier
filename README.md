# Lala Clasifier - A.I. Assisted Classification

![Version 1.0](https://img.shields.io/badge/version-1.0-green.svg)
[![php 7.*](https://img.shields.io/badge/php-7.*-8892BF.svg)](http://php.net/)
[![vue 2.*](https://img.shields.io/badge/vue-2.*-brightgreen.svg)](https://vuejs.org/)
[![php-ml latest](https://img.shields.io/badge/phpml-latest-004d40.svg)](https://github.com/php-ai/php-ml)

<p align="center">
	<img src="assets/lala_clasifier.png" alt="LalaClasifier" width="133" height="240" />
</p>

## About

**Lala***Clasifier* is an A.I. classifier based on PHP-ML library, it uses a set of labeled training data
of feedback items received from clients. The feedback labels are: 'spam', 'negative', 'neutral' and 'positive'.


## How does it work?

It normalizes the feedback input, transforms a collection of text samples to a vector of token counts, 
it then does a term frequency–inverse document frequency which is a numerical statistic that is intended 
to reflect how important a word is to a document in a collection or corpus. 
Using this weights it tries to assign the correct labels.

For training it uses the pre labeled samples split in to two random parts, one for training and one for
checking training accuracy.

In testing it obtained a 89.47% accuracy this can go up to 94% based on the training sample.

The precision per. label is as follows: Negative: 100.00% | Neutral: 83.33% | Positive: 100.00% | Spam: 87.50%

It some times scores lower especially for 'Neutral' and 'Spam' as it harder to differentiate between them. This 
is due to human bias on training samples labeling.

## API

The API is accessible via `JSON-POST` request at `lala.themeisle.com?api`

The following endpoints are relevant for usage:

**Training**

`lala.themeisle.com?api&req=train`

Pass to the `body` of the request an `array` containing a set of feedback and respective label to train with as follows:

```$json
[
  ...
  [ 'Feedback text to train with marked as spam', 'spam' ],
  [ 'Feedback text to train with marked as negative', 'negative' ],
  [ 'Feedback text to train with marked as positive', 'positive' ],
  [ 'Feedback text to train with marked as neutral', 'neutral' ],
  ...
]
```

The API will respond with the Accuracy for the training eg.:

```$json
{
  "accuracy":0.78947368421053,
  "precision": {
      "negative":1,
      "neutral":1,
      "positive":0,
      "spam":0.63636363636364
  }
}
```

**New Predictions**

`lala.themeisle.com?api&req=predict`

Pass to the `body` of the request an `array` containing a set of feedback to predict labels for as follows:

```$json
[
  ...
  'Feedback one text to predict label',
  'Feedback two text to predict label',
  'Feedback three text to predict label',
  'Feedback four text to predict label',
  ...
]
```

The API will respond with the labeled array eg.:

```$json
[
  ...
  [ 'Feedback one text to predict label', 'spam' ],
  [ 'Feedback two text to predict label', 'negative' ],
  [ 'Feedback three text to predict label', 'positive' ],
  [ 'Feedback four text to predict label', 'neutral' ],
  ...
]
```

### Notes

More features will be added soon, as needed by current usage. 

#### Authors

Bogdan Preda @bogdan.preda -- bogdan.preda@themeisle.com

Marius Cristea @selul -- marius.cristea@vertistudio.com
