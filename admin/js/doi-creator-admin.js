window.onload = function() {
    let createDOI = document.getElementsByClassName("createDOI");
    for(let i = 0; i < createDOI.length; i++) {
        createDOI[i].onclick = function () {
            var post_title = this.getAttribute('data-posttitle');
            var post_id = this.getAttribute('data-postid');
            document.getElementById('post_id').value = post_id;
            document.getElementById('xmlFileLabel').innerHTML = (post_title + ' (Create DOI for this article)');
            document.getElementById('viewXml').innerHTML = '';
        }
    }

    let submitDOI = document.getElementsByClassName("submitDOI");
    for(let i = 0; i < submitDOI.length; i++) {
        submitDOI[i].onclick = function () {
            var post_id = this.getAttribute('data-postid');
            var formData = new FormData();
            formData.append('post_id', post_id);
            var url = doi_settings.ajaxurl+'?action=submit_doi';
            var xhr = new XMLHttpRequest();
            xhr.responseType = 'json';
            xhr.open("POST", url, true);
            xhr.onload = function(e) {
                if ( this.status == 200 ) {
                    alert(this.response.msg);
                }
            };
            xhr.send(formData);
        }
    }
};

document.getElementById('submit-xml').addEventListener('click', function() {
    //validate_doi_form();
    var title = document.getElementById('title').value;
    var postid = document.getElementById('post_id').value;
    var elements = document.getElementById("doi-form");
    var formData = new FormData();
    for(var i=0; i<elements.length; i++)
    {
        formData.append(elements[i].name, elements[i].value);
    }
    var url = doi_settings.ajaxurl+'?action=generate_doi';
    var xhr = new XMLHttpRequest();
    xhr.responseType = 'json';
    xhr.open("POST", url, true);
    xhr.onload = function(e) {
        if ( this.status == 200 ) {
            if(this.response.file_url.length > 0){
                document.getElementById('viewXml').innerHTML = '<a target="_blank" class="btn btn-success" href="'+this.response.file_url+'">view xml</a>';
            }
            document.getElementById('successInfo').innerHTML = '<div class="bg-success"><p class="text text-white pt-3 pb-3">'+this.response.msg+'</p></div>';
        }
    };
        xhr.send(formData);
});

function validate_doi_form() {
    let issn = document.getElementById('series_issn').value;
    let doi = document.getElementById('paper_doi_data').value;
    let issnPattern = /^\d{4}-\d{3}[\dX]$/;
    if (!issnPattern.test(issn)) {
        alert('Invalid ISSN. Correct format: xxxx-xxx(x)');
        return false;
    }
	let doiPattern = /^10.\d{4,9}\/[-._;()/:A-Z0-9]+$/i;
	if (!doiPattern.test(doi)) {
		alert('Invalid DOI. Correct format: 10.xxxx/xxxxx');
		return false;
	}
	return true;
}
