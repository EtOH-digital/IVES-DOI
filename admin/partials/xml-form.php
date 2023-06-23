<?php $nonce = wp_create_nonce('submit_doi_nonce'); ?>
<div class="modal fade" id="createDOIModal" role="dialog" aria-labelledby="associateProductLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="xmlFileLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="info text-center" id="successInfo"></div>
                <form id="doi-form">
                    <input type="hidden" name="doi_nonce" value="<?php echo $nonce ?>">
                    <input type="hidden" name="post_id" value="" id="post_id">

                    <h5><?php _e('Series'); ?></h5>

                    <label><?php _e('Title *'); ?></label>
                    <input class="form-control" type="text" name="series_title" id="series_title" required>
                    <label><?php _e('ISSN *'); ?></label>
                    <input class="form-control" type="text" name="series_issn" id="series_issn" required>
                    <hr>

                    <h5 class="mt-2"><?php _e('Proceeding level'); ?></h5>

                    <label><?php _e('Proceeding Title *'); ?></label>
                    <input class="form-control" type="text" name="proceeding_title" id="proceeding_title" required>
                    <label><?php _e('Publisher *'); ?></label>
                    <input class="form-control" type="text" name="proceeding_publisher" id="proceeding_publisher" required>
                    <label><?php _e('Publisher Place *'); ?></label>
                    <input class="form-control" type="text" name="proceeding_publisher_place" id="proceeding_publisher_place" required>
                    <label><?php _e('Publication Date *'); ?></label>
                    <input class="form-control" type="date" name="proceeding_publish_date" id="proceeding_publish_date" required>
                    <hr>

                    <h5 class="mt-2"><?php _e('Conference Paper'); ?></h5>

                    <label><?php _e('Contributors *'); ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><?php _e('Name & Surname'); ?></span>
                        <input placeholder="Enter name" type="text" name="conference_paper_contributors_fname" aria-label="First name" class="form-control">
                        <input placeholder="Enter surname" type="text" name="conference_paper_contributors_sname" aria-label="Last name" class="form-control">
                    </div>
                    <label><?php _e('Titles *'); ?></label>
                    <input class="form-control" type="text" name="conference_paper_title" id="conference_paper_title" required>
                    <label><?php _e('Doi Data *'); ?></label>
                    <input class="form-control" type="text" name="conference_paper_doi_data" id="conference_paper_doi_data" required>
                    <hr>

                    <h5 class="mt-2"><?php _e('Series level'); ?></h5>

                    <label><?php _e('Doi Data'); ?></label>
                    <input class="form-control" type="text" name="series_doi_data" id="series_doi_data">
                    <label><?php _e('Contributors'); ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><?php _e('Name & Surname'); ?></span>
                        <input placeholder="Enter name" type="text" name="series_contributors_fname" aria-label="First name" class="form-control">
                        <input placeholder="Enter surname" type="text" name="series_contributors_sname" aria-label="Last name" class="form-control">
                    </div>
                    <label><?php _e('Series Number'); ?></label>
                    <input class="form-control" type="text" name="series_number" id="series_number">
                    <label><?php _e('ISBN'); ?></label>
                    <input class="form-control" type="text" name="series_isbn" id="series_isbn">
                    <hr>

                    <h5 class="mt-2"><?php _e('Conference level'); ?></h5>

                    <label><?php _e('Volume'); ?></label>
                    <input class="form-control" type="text" name="conference_volume" id="conference_volume">
                    <label><?php _e('ISBN'); ?></label>
                    <input class="form-control" type="text" name="conference_isbn" id="conference_isbn">
                    <label><?php _e('Conference Date'); ?></label>
                    <div class="input-group">
                        <span class="input-group-text"><?php _e('Start & End Date'); ?></span>
                        <input placeholder="Select start date" type="date" name="conference_date_start" aria-label="First name" class="form-control">
                        <input placeholder="Select end date" type="date" name="conference_date_end" aria-label="Last name" class="form-control">
                    </div>
                    <label><?php _e('Conference Location'); ?></label>
                    <input class="form-control" type="text" name="conference_location" id="conference_location">
                    <label><?php _e('Conference Acronym'); ?></label>
                    <input class="form-control" type="text" name="conference_acronym" id="conference_acronym">
                    <label><?php _e('Conference Theme'); ?></label>
                    <input class="form-control" type="text" name="conference_theme" id="conference_theme">
                    <label><?php _e('Conference Sponsor'); ?></label>
                    <input class="form-control" type="text" name="conference_sponsor" id="conference_sponsor">
                    <label><?php _e('Conference Number'); ?></label>
                    <input class="form-control" type="text" name="conference_number" id="conference_number">
                    <label><?php _e('Proceedings Subject'); ?></label>
                    <input class="form-control" type="text" name="proceedings_subject" id="proceedings_subject">
                    <hr>

                    <h5 class="mt-2"><?php _e('Conference paper'); ?></h5>
                    <label><?php _e('Conference Publication Date'); ?></label>
                    <input class="form-control" type="date" name="conference_publication_date" id="conference_publication_date">
                    <label><?php _e('Pages'); ?></label>
                    <input class="form-control" type="text" name="conference_pages" id="conference_pages">
                    <label><?php _e('Citation List'); ?></label>
                    <input class="form-control" type="text" name="conference_citation_list" id="conference_citation_list">
                    <label><?php _e('Funding'); ?></label>
                    <input class="form-control" type="text" name="conference_funding" id="conference_funding">
                    <label><?php _e('License'); ?></label>
                    <input class="form-control" type="text" name="conference_license" id="conference_license">

                    <label><?php _e('Crossmark Version'); ?></label>
                    <input class="form-control" type="text" name="conference_crossmark_version" id="conference_crossmark_version">

                    <label><?php _e('Crossmark Policy'); ?></label>
                    <input class="form-control" type="text" name="conference_crossmark_policy" id="conference_crossmark_policy">
                </form>
            </div>
            <div class="modal-footer">
                <div id="viewXml"></div>
                <button id="submit-xml" type="button" class="btn btn-primary"><?php _e('Generate XML'); ?></button>
            </div>
        </div>
    </div>
</div>
