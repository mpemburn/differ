$(document).ready(function ($) {
    window.Differ = null;
    class Differ {
        constructor() {
            this.screenshotList = $('#screenshots');
            this.loading = $('#loading');
            this.imageArea = $('#image_area');
            this.diffArea = $('#diff_area');
            this.images = $('.test-image');
            this.zones = $('.image-zone');
            this.diffImage = $('#diff_image');
            this.diffMessage = $('#diff_msg');
            this.comparing = $('#comparing');
            this.clearButton = $('#clear_button');
            this.fileData = {};
            this.labels = {
                before_image: 'Before Screenshot',
                after_image: 'After Screenshot',
                diff_image: 'Difference'
            };
            this.currentImage = null;
            this.currentItem = this.getQueryValue('item')
            this.autoMode = this.getQueryValue('auto') === 'true';
            this.nextItem = this.getNextItem();

            this.addListeners();

            this.routeByQueryParam();
        }

        getQueryValue(key) {
            let queryString = window.location.search;
            let urlParams = new URLSearchParams(queryString);

            return urlParams.get(key);
        }

        routeByQueryParam() {
            switch (true) {
                case this.currentItem.length > 0:
                    this.processItem(this.currentItem);
                    break;
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
            let params = window.location.search.split('&');
            let newParams = [];

            params.forEach(function (value) {
                if (value.includes('item=')) {
                    let valParts = value.split('=');
                    let current = parseInt(valParts[1])
                    let next = current + 1;
                    value = 'item=' + next;
                }
                newParams.push(value);
            });

            return newParams.join('&');
        }

        loadNext() {
            if (this.autoMode && this.currentItem < $('option[value]').length) {
                window.location = window.location.origin + window.location.pathname + this.nextItem;
            } else {
                alert('that is all!');
            }
        }

        saveResults(filename, data) {
            console.log(filename);
            console.log(data);
        }

        clearData() {
            console.log('Before clear: ');
            console.log(this.fileData);
            this.fileData = {
                name: null,
                before: null,
                after: null
            };
            console.log('After clear: ');
            console.log(this.fileData);
        }

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
        }

        onComplete(data) {
            let time = Date.now();
            let diffImage = new Image();
            diffImage.src = data.getImageDataUrl();
            //console.log(data);

            // console.log(window.Differ.currentImage);
            window.Differ.saveResults(window.Differ.currentImage, data);
            window.Differ.loading.hide();

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
            window.Differ.clearData();
            window.Differ.loadNext();
        }
    }

    window.Differ = new Differ();
});
