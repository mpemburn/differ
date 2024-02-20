$(document).ready(function ($) {
    class Screenie {
        constructor() {
            this.fetchImages();
            // console.log("here I am!");
            this.addListeners();
        }

        fetchImages() {
            let self = this;

            self.ajaxSetup()
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: "/fetch_images",
                success: function (data) {
                    let before = atob(data.images.before);
                    let after = atob(data.images.after);
                    // console.log(before);
                    var diff = resemble(before)
                        .compareTo(after)
                        .ignoreColors()
                        .onComplete(function (data) {
                            console.log(data);
                        });
                },
                error: function (msg) {
                    console.log(msg);
                }
            });

        }

        base64ToBlob(base64Data, contentType = 'application/octet-stream') {
            let cleanedBase64Data = base64Data.replace(/^data:image\/[a-z]+;base64,/, '');
            let byteCharacters = atob(cleanedBase64Data);
            let byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            let byteArray = new Uint8Array(byteNumbers);

            return new Blob([byteArray], {type: contentType});
        };

        addListeners() {

        }

        ajaxSetup() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    }

    new Screenie();
});
