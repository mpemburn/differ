$(document).ready(function ($) {
    window.Differ = null;
    class Differ {
        constructor() {
            this.screenshotList = $('#screenshots');
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

            this.addListeners();

            this.routeByQueryParam();
        }

        routeByQueryParam() {
            let queryString = window.location.search;
            let urlParams = new URLSearchParams(queryString);

            switch (true) {
                case urlParams.get('auto') === 'true':
                    this.doAutoScan();
            }
        }

        doAutoScan() {
            let self = this;

            this.images.each(function () {
                let when = $(this).data('when');
                if (when === 'before') {
                    $(this).trigger('click');
                }
            })
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

        processData() {
            this.currentImage = this.fileData.name;
            resemble(this.fileData.before)
                .compareTo(this.fileData.after)
                .onComplete(this.onComplete);
        }


        addListeners() {
            let self = this;

            this.screenshotList.on('change', function () {
                let name = $(this).val();
                let image1 = $('[data-name="' + name + '"][data-when="before"]');
                let image2 = $('[data-name="' + name + '"][data-when="after"]');
                let src1 = image1.data('url');
                let src2 = image2.data('url');

                self.currentImage = name;
                self.diffImage.html('');
                self.comparing.html('Comparing: ' + name);
                self.clearButton.show();
                self.diffMessage.show();

                $("#before_image").html('<img src="' + src1 + '" alt="before"/>');
                $("#after_image").html('<img src="' + src2 + '" alt="after"/>');

                //self.clearData();
                self.getFileData(name, src1, 'before');
                self.getFileData(name, src2, 'after');
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
        }
    }

    window.Differ = new Differ();
});
