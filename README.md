## Description

MyHashTags: A jQuery twitter plugin.  Shows the latest tweets from a list of hashtags.

The latest version of MyHashTags support the Twitter API v1.1

## Getting Started

1. Include the JS file into your page after jQuery:
```
<script src="http://code.jquery.com/jquery.min.js"></script>
<script src="jquery.myhashtags.js"></script>
```

2. Call the myhashtags function to any div element:
```
	<script>
		$(function()
		{
			$('#divTweets').myhashtags({q: '#jquery,#javascript', count: '15', lang: 'es'});
		});
	</script>
```

3. Create you empty container and give it a id:
```
	<div id="divTweets"></div>
```

3. On config.php complete your Twitter settings:
```
OAUTH_TOKEN
OAUTH_TOKEN_SECRET
CONSUMER_KEY
CONSUMER_SECRET
```

## License
Copyright (c) 2013 Vicente García Dorado Licensed under the GPL license.