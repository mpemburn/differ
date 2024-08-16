$(document).ready(function ($) {
    window.Commands = null;

    class Commands {
        constructor() {
            this.commandResults = $('#command_results');
            this.loading = $('#loading');
            this.urlArray = [];
            this.filename = null;
            this.testName = null;
            this.when = null;
        }

        run(filename, testName, when, result) {
            let self = this;
            this.filename = filename;
            this.testName = testName;
            this.when = when;
            this.results = results;

            $.ajax({
                type: "GET",
                url: "/get_file_list",
                data: $.param({
                    filename: filename,
                }),
                processData: false,
                success: function (data) {
                    self.urlArray = data.urls;
                    self.iterateUrls();
                },
                error: function (msg) {
                    console.log(msg);
                }
            });
        }

        iterateUrls() {
            if (this.urlArray.length === 0) {
                return;
            }
            let url = this.urlArray.shift();
            this.results.append(url);
            this.executeCommand(url);
        }

        executeCommand(url) {
            if (url === '') {
                return;
            }
            let self = this;
            this.ajaxSetup();

            this.commandResults.show();
            this.loading.show();

            $.ajax({
                type: "POST",
                url: "/execute",
                data: $.param({
                    url: url,
                    filename: this.filename,
                    testName: this.testName,
                    when: this.when,
                }),
                processData: false,
                success: function (data) {
                    self.results.append(' —Done' + "\n");
                    self.loading.hide();
                    self.iterateUrls();
                },
                error: function (msg) {
                    console.log(msg);
                }
            });

        }
        ajaxSetup() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    }

    window.Commands = new Commands();
});
