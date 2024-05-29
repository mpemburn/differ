$(document).ready(function ($) {
    window.Differ = null;
    window.currentImage = null;

    class Differ {
        constructor() {
            this.sourceList = $('#sources');
            this.screenshotList = $('#screenshots');
            this.loading = $('#loading');
            this.images = $('.test-image');
            this.zones = $('.image-zone');
            this.diffImage = $('#diff_image');
            this.diffMessage = $('#diff_msg');
            this.comparing = $('#comparing');
            this.clearButton = $('#clear_button');
            this.automateButton = $('#automate_button');
            this.resultsButton = $('#results_button');
            this.resultsList = $('#results_list tbody');
            this.resultsModal = $('#results_modal');
            this.fileData = {};
            this.labels = {
                before_image: 'Before Screenshot',
                after_image: 'After Screenshot',
                diff_image: 'Difference'
            };
            this.autoMode = false;
            this.testNumber = $('#test_number').html();
            this.imageCollection = {}

            this.createImageCollection();
            this.addListeners();

            this.sourceList.val(this.getQueryValue('source'));
        }

        getQueryValue(key) {
            let queryString = location.search;
            let urlParams = new URLSearchParams(queryString);

            return urlParams.get(key);
        }

        runAutoMode() {
            let self = this;
            this.iterateCollection();
        }

        haltAutoMode() {
            this.autoMode = false;
            // Rebuild collection after autoMode has wiped it out.
            this.createImageCollection();
        }

        iterateCollection() {
            let key = Object.keys(this.imageCollection)[0];
            let value = Object.values(this.imageCollection)[0];

            window.currentImage = key;
            this.prepareDataForComparison(value);

            delete this.imageCollection[key];

            if (Object.keys(this.imageCollection).length === 0) {
                this.haltAutoMode();
            }
        }

        ajaxSetup() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        saveResults(filename, data) {
            let self = this;

            this.ajaxSetup()
            $.ajax({
                type: "POST",
                url: "/save_results",
                data: $.param({
                    source: this.getQueryValue('source'),
                    testNumber: this.testNumber,
                    filename: filename,
                    percentage: data.misMatchPercentage
                }),
                processData: false,
                success: function (data) {
                    console.log(data);
                },
                error: function (msg) {
                    console.log(msg);
                }
            });
        }

        getResults() {
            let self = this;
            let source = this.getQueryValue('source')
            $.ajax({
                type: "GET",
                url: "/get_results",
                data: $.param({
                    source: source
                }),
                processData: false,
                success: function (data) {
                    self.resultsList.empty();
                    $('#results_source').html(source);
                    data.results.forEach(function (value) {
                        self.resultsList.append('<tr>')
                        self.resultsList.append('<td data-name="' + value.filename + '">' + value.filename + '</td>')
                        self.resultsList.append('<td>' + value.diff_percentage + '%</td>')
                        self.resultsList.append('</tr>')
                    });
                    $('td[data-name]').on('click', function () {
                        let name = $(this).data('name');
                        let imageData = self.imageCollection[name];

                        self.resultsModal.modal('hide');
                        self.prepareDataForComparison(imageData);
                    });

                    self.resultsModal.modal('show');
                },
                error: function (msg) {
                    console.log(msg);
                }
            });
        }

        redirect(queryString) {
            location = location.origin + location.pathname + queryString;
        }

        clearData() {
            this.fileData = {
                name: null,
                before: null,
                after: null
            };
        }

        // Retrieve binary file data and process it
        fetchBinaryFileData(filename, imgSrc, when) {
            let self = this;

            fetch(imgSrc)
                .then(res => res.blob())
                .then(blob => {
                    self.fileData[when] = new File([blob], name, blob)

                    if (self.fileData.before && self.fileData.after) {
                        self.fileData.name = filename;
                        self.compareWithResembleJs();
                    }
                });
        }

        createImageCollection() {
            let collection = {};
            this.screenshotList.children().each(function () {
                let dataset = $(this)[0].dataset;
                let parts = {};

                if (dataset.when === 'after') {
                    parts['after']  = dataset;
                    collection[dataset.name] = parts;
                }
                if (dataset.when === 'before') {
                    parts = collection[dataset.name];
                    parts['before']  = dataset;
                    collection[dataset.name] = parts;
                }
            });

            this.imageCollection = collection;
        }

        prepareDataForComparison(imageData) {
            let beforeImage = imageData['before']['url'];
            let afterImage = imageData['after']['url'];
            let name = imageData['before']['name'];

            this.diffImage.html('');
            this.comparing.html('Comparing: ' + name);
            this.clearButton.show();
            this.diffMessage.show();
            this.loading.show();

            $("#before_image").html('<img src="' + beforeImage + '" alt="before"/>');
            $("#after_image").html('<img src="' + afterImage + '" alt="after"/>');

            this.fetchBinaryFileData(name, beforeImage, 'before');
            this.fetchBinaryFileData(name, afterImage, 'after');
        }

        compareWithResembleJs() {
            this.currentImage = this.fileData.name;
            resemble(this.fileData.before)
                .compareTo(this.fileData.after)
                .onComplete(this.resembleComplete);
        }

        resembleComplete(data) {
            let differ = window.Differ;
            let time = Date.now();
            let diffImage = new Image();
            let heightDiff = Math.abs(data.dimensionDifference.height).toLocaleString();
            let heightMessage = (heightDiff !== 0)
                ? 'The two images differ in height by ' + heightDiff + ' pixels' : '';

            diffImage.src = data.getImageDataUrl();
            // console.log(window.currentImage);

            // console.log(window.Differ.currentImage);
            differ.saveResults(window.currentImage, data);
            differ.loading.hide();

            $("#diff_image").html(diffImage);
            $("#percentage").html('The "after" image differs from the "before" image by ' + data.misMatchPercentage + '%');
            $("#height_diff").html(heightMessage);

            $(diffImage).addClass('difference').click(function() {
                var w = window.open("about:blank", "_blank");
                var html = w.document.documentElement;
                var body = w.document.body;

                html.style.margin = 0;
                html.style.padding = 0;
                body.style.margin = 0;
                body.style.padding = 0;

                var img = w.document.createElement("img");
                img.src = diffImage.src;
                img.alt = "image diff";
                img.style.maxWidth = "100%";
                img.addEventListener("click", function() {
                    this.style.maxWidth =
                        this.style.maxWidth === "100%" ? "" : "100%";
                });
                body.appendChild(img);
            });

            data = null;
            if (differ.autoMode) {
                setTimeout(function () {
                    differ.clearData();
                    differ.iterateCollection();
                }, 500);
            }
        }

        addListeners() {
            let self = this;

            this.sourceList.on('change', function () {
                let source = $(this).val();

                self.redirect('?source=' + source);
            });

            this.screenshotList.on('change', function () {
                let imageName = $(this).find("option:selected").data('name');
                let imageData = self.imageCollection[imageName];

                self.prepareDataForComparison(imageData)
            });

            this.clearButton.on('click', function () {
                self.zones.each(function () {
                    let zone = $(this);
                    let id = zone.attr('id');

                    zone.empty()
                    zone.html(self.labels[id]);
                });
                self.comparing.empty();
                self.diffMessage.hide();
                self.clearButton.hide();
                self.screenshotList.val('');
            });

            this.automateButton.on('click', function () {
                self.autoMode = true;
                self.runAutoMode();
            });

            this.resultsButton.on('click', function () {
                self.getResults();
            });
        }
    }

    window.Differ = new Differ();
});
