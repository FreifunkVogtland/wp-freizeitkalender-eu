document.addEventListener('DOMContentLoaded', function () {
    const vueElementId = 'freizeitkalender-eu-list';
	if (document.getElementById(vueElementId) !== null) {
		const freizeit_kalender = new Vue({
			el: '#' + vueElementId,
			data: {
				kalender_event_list: [],
				selected_date: new Date(),
				kalender_event_list_loading: false,
			},
			methods: {
				get_freizeitkalender_eu(path) {
					return new Promise(function (resolve, reject) {
						const xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function () {
							if (this.readyState === 4) {
								if (this.status === 200) {
									const json_string = this.responseText;
									resolve(JSON.parse(json_string));
								} else {
									reject(this.status);
								}
							}
						};
						xhttp.open("GET", path, true);
						xhttp.send();
					});
				},
				load_kalender_event_list() {
					const that = this;
					// Array leeren
					that.kalender_event_list.splice(0, that.kalender_event_list.length);
					that.kalender_event_list_loading = true;
					this.get_freizeitkalender_eu('/wp-json/freizeitkalender-eu/v2/event-list-by-limit/1').then(function (kalender_event_list) {
						that.kalender_event_list_loading = false;
						kalender_event_list.forEach(function (kalender_event) {
							that.kalender_event_list.push(kalender_event);
						});
					});
				},
			}
		});

		freizeit_kalender.load_kalender_event_list();
	}
});
