$(document).ready(function ($) {
    class Differ {
        constructor() {
            this.fileList = $('#file_list');
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
            this.addListeners();
        }

        addListeners() {
            let self = this;

            this.images.on('click', function () {
                let image1 = $(this);
                let name = image1.data('name')
                let image2 = $('[data-name="' + name + '"][data-when="after"]');
                let src1 = image1.data('url');
                let src2 = image2.data('url');

                self.diffImage.html('');
                self.comparing.html('Comparing: ' + name);
                self.clearButton.show();
                self.diffMessage.show();

                $("#before_image").html('<img src="' + src1 + '" alt="before"/>');
                $("#after_image").html('<img src="' + src2 + '" alt="after"/>');

                self.clearData();
                self.getFileData(src1, 'before');
                self.getFileData(src2, 'after');
                self.fileList.removeClass('mouse-over');
                self.imageArea.removeClass('push-right')
                self.diffArea.removeClass('push-right')
            });

            this.fileList.on('mouseover', function () {
                $(this).addClass('mouse-over');
                self.imageArea.addClass('push-right')
                self.diffArea.addClass('push-right')

            }).on('mouseout', function () {
                $(this).removeClass('mouse-over');
                self.imageArea.removeClass('push-right')
                self.diffArea.removeClass('push-right')
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
            });
        }

        clearData() {
            this.fileData = {
                before: null,
                after: null
            };
        }

        getFileData(imgSrc, when) {
            let self = this;

            fetch(imgSrc)
                .then(res => res.blob())
                .then(blob => {
                    self.fileData[when] = new File([blob], name, blob)

                    if (self.fileData.before && self.fileData.after) {
                        self.processData();
                    }
                });
        }

        processData() {
            resemble(this.fileData.before)
                .compareTo(this.fileData.after)
                .onComplete(this.onComplete);
        }

        onComplete(data) {
            let time = Date.now();
            let diffImage = new Image();
            diffImage.src = data.getImageDataUrl();
            console.log(data);

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
        }
    }

    new Differ();
});
