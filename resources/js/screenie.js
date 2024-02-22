$(document).ready(function ($) {
    class Screenie {
        constructor() {
            this.images = $('.test-image');
            this.diffImage = $("#image-diff");
            this.fileData = {};
            this.addListeners();
        }

        addListeners() {
            let self = this;

            this.images.on('click', function () {
                let image = $(this);
                let name = image.data('name')
                let src1 = image.data('url');
                let image2 = $('[data-name="' + name + '"][data-when="after"]');
                let src2 = image2.data('url');

                $("#image-diff").html('');
                // self.diffImage.empty();

                $("#dropzone1").html('<img src="' + src1 + '"/>');
                $("#dropzone2").html('<img src="' + src2 + '"/>');

                self.clearData();
                self.getFileData(src1, 'before');
                self.getFileData(src2, 'after');
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

            $("#image-diff").html(diffImage);

            $(diffImage).click(function() {
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

    new Screenie();
});
