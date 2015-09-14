    /**
	 * Toggle the read state of an element
	 *
	 * @param {object} el    The DOM element
	 * @param {string} id    The ID of the target element
	 *
	 * @returns {boolean}
	 */
AjaxRequest.toggleNcContactFormRead = function(el, id) {
	el.blur();
	var image = $(el).getFirst('img'),
		read = (image.src.indexOf('unread') == -1),
		div = el.getParent('div').getParent('div');
	if (read) {
		image.src = image.src.replace('read.png', 'unread.png');
		new Element('strong', {
			html: div.getFirst('i').get('html')
		}).replaces(div.getFirst('i'));
		new Request.Contao({'url':window.location.href, 'followRedirects':false}).get({'item':id, 'read_state':0, 'rt':Contao.request_token});
	} else {
		image.src = image.src.replace('unread.png', 'read.png');
		new Element('i', {
			html: div.getFirst('strong').get('html')
		}).replaces(div.getFirst('strong'));
		new Request.Contao({'url':window.location.href, 'followRedirects':false}).get({'item':id, 'read_state':1, 'rt':Contao.request_token});
	}
	return false;
};