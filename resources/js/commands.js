$(document).ready(function ($) {
    window.Commands = null;

    class Commands {
        constructor() {
            this.urlArray = [];
            this.testName = null;
            this.when = null;
        }

        run(filename, testName, when, result) {
            let self = this;
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
            let url = this.urlArray.shift();
            if (this.urlArray.length === 0 || url === '') {
                return;
            }
            this.results.append(url);
            this.executeCommand(url);
        }

        executeCommand(url) {
            let self = this;
            this.ajaxSetup();

            $.ajax({
                type: "POST",
                url: "/execute",
                data: $.param({
                    url: url,
                    testName: this.testName,
                    when: this.when,
                }),
                processData: false,
                success: function (data) {
                    self.results.append(' —Done' + "\n");
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
