window.onload = function() {
    let createDOI = document.getElementsByClassName("createDOI");
    for (let i = 0; i < createDOI.length; i++) {
        createDOI[i].onclick = function () {
            document.getElementById('doi_post_id').value = this.getAttribute('data-postid');
            //console.log(this.getAttribute('data-posttitle'));
            document.getElementById('formTitle').innerHTML = '<h2>' + this.getAttribute('data-posttitle') + ' (Create DOI for this article)</h2>';
            document.getElementById('viewXml').innerHTML = '';
            document.getElementById('successInfo').innerHTML = '';
        }
    }

    let submitDOI = document.getElementsByClassName("submitDOI");
    for (let i = 0; i < submitDOI.length; i++) {
        submitDOI[i].onclick = function () {
            var formData = new FormData();
            formData.append('post_id', this.getAttribute('data-postid'));
            var url = doi_settings.ajaxurl + '?action=submit_doi';
            var xhr = new XMLHttpRequest();
            xhr.responseType = 'json';
            xhr.open("POST", url, true);
            xhr.onload = function (e) {
                if (this.status == 200) {
                    alert(this.response.msg);
                }
            };
            xhr.send(formData);
        }

    }

    document.getElementById('submit-xml').addEventListener('click', function (event) {
        event.preventDefault();
        if (!validate_doi_form()) {
            alert('Invalid ISSN. Correct format: xxxx-xxx(x)');
            return;
        }
        var title = document.getElementById('title').value;
        var postID = document.getElementById('doi_post_id').value;
        //var elements = document.getElementById("submit-doi-form");
        var elements = document.getElementById('myForm').getElementsByTagName('input');
        var formData = new FormData();
        for (var i = 0; i < elements.length; i++) {
            formData.append(elements[i].name, elements[i].value);
        }
        var url = doi_settings.ajaxurl + '?action=generate_doi';
        var xhr = new XMLHttpRequest();
        xhr.responseType = 'json';
        xhr.open("POST", url, true);
        xhr.onload = function (e) {
            if (this.status == 200) {
                if (this.response.file_url.length > 0) {
                    document.getElementById('viewXml').innerHTML = '<a target="_blank" class="button button-primary" href="' + this.response.file_url + '">view xml</a>';
                    //document.getElementById(postID).appendChild = '<span data-postid="' + postID + '" class="button button-primary submitDOI">Submit DOI</span>';
                }
                document.getElementById('successInfo').innerHTML = '<div class="infoMsg"><h4>' + this.response.msg + '</h4></div>';
            }
        };
        xhr.send(formData);
    });

    function validate_doi_form() {
        let issn = document.getElementById('series_issn').value;
        //let doi = document.getElementById('paper_doi_data').value;
        let issnPattern = /^\d{4}-\d{3}[\dX]$/;
        if (!issnPattern.test(issn)) {
            return false;
        }
        /*let doiPattern = /^10.\d{4,9}\/[-._;()/:A-Z0-9]+$/i;
        if (!doiPattern.test(doi)) {
            alert('Invalid DOI. Correct format: 10.xxxx/xxxxx');
            return false;
        }*/
        return true;
    }
}
