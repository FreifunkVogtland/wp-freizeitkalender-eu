// Subfolder in Wordpress (hat bei mir nicht geklappt)
// https://www.storelocatorplus.com/wordpress-subdirectory-installs-and-the-rest-api/
// https://stackoverflow.com/questions/56930084/how-do-i-configure-nginx-for-wordpress-rest-api-in-sub-folder
// statt /wp-json/ => /sub_folder/index.php?rest_route=
// todo Übergabe Subfolder, wenn vorhanden und korrektur der Route
// Einfügen als Data Attribute in Template
document.addEventListener('DOMContentLoaded', function () {
    const vueElementId = 'freizeitkalender-eu';
	if (document.getElementById(vueElementId) !== null) {
		const freizeit_kalender = new Vue({
			el: '#' + vueElementId,
			data: {
				kalender_event_list: [],
				selected_date: new Date(),
				kalender_event_list_loading: false,
			},
			methods: {
				get_selected_date_for_display() {
					const date_options = {year: 'numeric', month: 'long'};
					return this.selected_date.toLocaleDateString('de-DE', date_options);
				},
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
				load_kalender_event_description(kalender_event_list_key) {
					const that = this;
					this.get_freizeitkalender_eu('/wp-json/freizeitkalender-eu/v2/event-detail/' + that.kalender_event_list[kalender_event_list_key].id).then(function (kalender_event_detail) {
						that.kalender_event_list[kalender_event_list_key].description = kalender_event_detail.description;
						that.kalender_event_list[kalender_event_list_key].is_description_loaded = true;
					});
				},
				load_kalender_event_list() {
					const that = this;
					// Array leeren
					that.kalender_event_list.splice(0, that.kalender_event_list.length);
					that.kalender_event_list_loading = true;
					const month_string = that.selected_date.toISOString().substr(0, 7);
					this.get_freizeitkalender_eu('/wp-json/freizeitkalender-eu/v2/event-list-by-month/' + month_string).then(function (kalender_event_list) {
						that.kalender_event_list_loading = false;
						kalender_event_list.forEach(function (kalender_event) {
							that.kalender_event_list.push(kalender_event);
						});
					});
				},
				set_next_month() {
					this.selected_date.setMonth(this.selected_date.getMonth() + 1);
					this.load_kalender_event_list();
				},
				set_prev_month() {
					const current_date = new Date();
					if (this.selected_date <= current_date) {
						return;
					}
					this.selected_date.setMonth(this.selected_date.getMonth() - 1);
					this.load_kalender_event_list();
				},
				set_current_month() {
					this.selected_date = new Date();
					this.selected_date.setDate(1);
					this.load_kalender_event_list();
				}
			}
		});

		freizeit_kalender.set_current_month();
	}
});
