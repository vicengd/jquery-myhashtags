/*
 * MyHashTags: A jQuery twitter plugin. Shows the latest tweets from a list of hashtags - v1.0 - 12/11/2013
 * Copyright (c) 2013 Vicente Garcia Dorado (v@vicentegarcia.com)
 * Licensed under GPL (http://opensource.org/licenses/gpl-license.php) licenses.
 */
(function($) {
	/* constructor for Plugin */
	$.fn.myhashtags = function(options){
		var opts = $.extend({},$.fn.myhashtags.defaultOptions,options);

		return this.each(function (options) {
			var tweets;
			var context = $(this);
			var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
			var strMonth = '';

			$.ajax({
				url: './TwitterSimpleAPI.php',
				dataType: 'json',
				data: {q: opts.q, count: opts.count, lang: opts.lang}
			})
			.done(function(data) {
				var statuses = data['statuses'];
			  	$.each(statuses, function(i, item){
          			if (i == 0) {
                        tweets = '<ul>';
                    }
                    for(var i=0; i<=12; i++) {
                		if(months[i] == item['created_at'].substr(4, 3)) {
							strMonth = i + 1;
							if(strMonth < 10) {
								strMonth = '0' + strMonth;
							}
                   		}
                    }
                    var date_str = item['created_at'].substr(8, 2) + '/' + strMonth + '/' + item['created_at'].substr(28,2) + ' - ' + item['created_at'].substr(11,5);
                    tweets += '<li><span class="datetweet">'+ date_str + '</span>: ' + item['text'] +'</li>';
                });
				tweets += '</ul>';
				context.append(tweets);
			});

		});
	};
	/* End the Plugin */

	$.fn.myhashtags.defaultOptions = {
	    q : '#jquery',
	    count : '15',
	    lang : 'es'
	};

})(jQuery);