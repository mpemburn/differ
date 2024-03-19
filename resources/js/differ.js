$(document).ready(function ($) {
    window.Differ = null;
    class Differ {
        constructor() {
            this.sourceList = $('#sources');
            this.screenshotList = $('#screenshots');
            this.loading = $('#loading');
            this.selectScreenshot = $('#select_screenshot');
            this.titleArea = $('#title_area');
            this.imageArea = $('#image_area');
            this.diffArea = $('#diff_area');
            this.images = $('.test-image');
            this.zones = $('.image-zone');
            this.diffImage = $('#diff_image');
            this.diffMessage = $('#diff_msg');
            this.comparing = $('#comparing');
            this.clearButton = $('#clear_button');
            this.automateButton = $('#automate_button');
            this.fileData = {};
            this.labels = {
                before_image: 'Before Screenshot',
                after_image: 'After Screenshot',
                diff_image: 'Difference'
            };
            this.currentImage = null;
            this.currentItem = this.getQueryValue('item');
            this.autoMode = this.getQueryValue('auto') === 'true';
            this.nextItem = this.getNextItem();
            this.imageCount = $('option[value][data-when]').length;
            this.testNumber = 0;

            this.addListeners();

            if (this.autoMode) {
                this.runAutoMode();
            }

            if (this.getQueryValue('done') === 'true') {
                this.persistResults();
            }

            this.sourceList.val(this.getQueryValue('source'));
        }

        getQueryValue(key) {
            let queryString = location.search;
            let urlParams = new URLSearchParams(queryString);

            return urlParams.get(key);
        }

        runAutoMode() {
            if (! this.currentItem) {
                this.redirect(location.search + '&item=1');
            }

            if (this.currentItem && this.currentItem.length === 1) {
                this.selectScreenshot.hide();
                this.titleArea.hide();
                this.processItem(this.currentItem);
            }
        }

        processItem(itemNumber) {
            let image = this.screenshotList.find('option[value="' + itemNumber+ '"]');
            let name = image.is('*') ? image.data('name') : null;
            if (name) {
                this.compareImages(name);
            }
        }

        getNextItem() {
            if (! this.autoMode) {
                return;
            }
            let self = this;
            let params = location.search.split('&');
            let newParams = [];

            params.forEach(function (value) {
                if (value.includes('item=')) {
                    let valParts = value.split('=');
                    let current = parseInt(valParts[1])
                    let lastItem = $('option[value][data-when]').length;
                    let next = current + 1;
                    if (next > lastItem) {
                        self.haltAutoMode();
                        return;
                    } else {
                        value = 'item=' + next;
                    }
                }
                newParams.push(value);
            });

            return newParams.join('&');
        }

        loadNext() {
            if (this.autoMode && this.currentItem < this.imageCount) {
                this.redirect(this.nextItem);
            } else {
                this.selectScreenshot.show();
                this.titleArea.show();
            }
        }

        haltAutoMode() {
            let source = '?source=' + this.getQueryValue('source');
            let done = location.origin + location.pathname + source + '&done=true';

            setTimeout(function () {
                location.replace(done);
            }, 500);

            throw new Error('Halt processing');
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
                    self.loadNext();

                    console.log(data);
                },
                error: function (msg) {
                    console.log(msg);
                }
            });
        }

        persistResults() {
            let self = this;

            this.ajaxSetup()
            $.ajax({
                type: "POST",
                url: "/persist_results",
                data: $.param({
                    source: this.getQueryValue('source')
                }),
                processData: false,
                success: function (data) {
                    console.log(data);
                    self.getResults();
                },
                error: function (msg) {
                    console.log(msg);
                }
            });
        }

        getResults() {
            $.ajax({
                type: "GET",
                url: "/get_results",
                data: $.param({
                    source: this.getQueryValue('source')
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

        insertUrlParam(key, value) {
            if (history.pushState) {
                let searchParams = new URLSearchParams(location.search);
                searchParams.set(key, value);
                let newUrl = location.protocol + "//" + location.host + location.pathname + '?' + searchParams.toString();
                history.pushState({path: newUrl}, '', newUrl);
            }
        }

        redirect(queryString) {
            location = location.origin + location.pathname + queryString;
        }

        ajaxSetup() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        clearData() {
            this.fileData = {
                name: null,
                before: null,
                after: null
            };
        }

        // Retrieve binary file data and process it
        getFileData(filename, imgSrc, when) {
            let self = this;

            fetch(imgSrc)
                .then(res => res.blob())
                .then(blob => {
                    self.fileData[when] = new File([blob], name, blob)

                    if (self.fileData.before && self.fileData.after) {
                        self.fileData.name = filename;
                        self.processData();
                    }
                });
        }

        compareImages(name) {
            let image1 = $('[data-name="' + name + '"][data-when="before"]');
            let image2 = $('[data-name="' + name + '"][data-when="after"]');
            let src1 = image1.data('url');
            let src2 = image2.data('url');

            this.currentImage = name;
            this.diffImage.html('');
            this.comparing.html('Comparing: ' + name);
            this.clearButton.show();
            this.diffMessage.show();
            this.loading.show();

            $("#before_image").html('<img src="' + src1 + '" alt="before"/>');
            $("#after_image").html('<img src="' + src2 + '" alt="after"/>');

            //this.clearData();
            this.getFileData(name, src1, 'before');
            this.getFileData(name, src2, 'after');
        }

        processData() {
            this.currentImage = this.fileData.name;
            resemble(this.fileData.before)
                .compareTo(this.fileData.after)
                .onComplete(this.onComplete);
        }

        addListeners() {
            let self = this;

            this.sourceList.on('change', function () {
                let source = $(this).val();

                self.redirect('?source=' + source);
            });

            this.screenshotList.on('change', function () {
                let name = $(this).find("option:selected").data('name');
                self.compareImages(name)
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
                let source = self.sourceList.val();

                self.redirect('?source=' + source + '&auto=true');
            });
        }

        onComplete(data) {
            let differ = window.Differ;
            let time = Date.now();
            let diffImage = new Image();
            diffImage.src = data.getImageDataUrl();
            //console.log(data);

            // console.log(window.Differ.currentImage);
            differ.saveResults(differ.currentImage, data);
            differ.loading.hide();

            $("#diff_image").html(diffImage);
            $("#percentage").html('The "after" image differs from the "before" image by ' + data.misMatchPercentage + '%');

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
            differ.clearData();
            // differ.loadNext();
        }
    }

    window.Differ = new Differ();
});
