var storify = storify || {};
storify.dev = storify.dev || {};
storify.dev.template = storify.dev.template || {};

storify.dev.template.project_brief = (data) =>`
    <div class="detailcontent" id="brief-content">
        <div class="valueblock">
            <label>Title</label>
            <div class="content">
                ${data.title}
            </div>
        </div>
        <div class="valueblock">
            <label>Location</label>
            <div class="content">
                ${
                    (locations=>{
                        if(locations && locations.length){
                            return locations.map(location=>{
                                if(location.term_id){
                                    return `<span data-term_id="${location.term_id}">${location.name}</span>`;
                                }
                            }).join(", ");
                        }else{
                            return "N/A";
                        }
                    })(data.summary.location)
                }
            </div>
        </div>
        <div class="valueblock">
            <label>Brand</label>
            <div class="content">
                ${
                    (brands=>{
                        if(brands && brands.length){
                            return brands.map(brand=>{
                                if(brand.term_id){
                                    return `<span data-term_id="${brand.term_id}">${brand.name}</span>`;
                                }
                            }).join(", ");
                        }else{
                            return "N/A";
                        }
                    })(data.summary.brand)
                }
            </div>
        </div>
        <div class="valueblock">
            <label>Passion</label>
            <div class="content">
                ${
                    (tags=>{
                        if(tags && tags.length){
                            return tags.map(tag=>{
                                if(tag.term_id){
                                    return `<span data-term_id="${tag.term_id}">${tag.name}</span>`;
                                }
                            }).join(", ");
                        }else{
                            return "N/A";
                        }
                    })(data.summary.tag)
                }
            </div>
        </div>
        <div class="valueblock">
            <label># of creators</label>
            <div class="content">
                Total : ${data.summary.offer.data.length}<br>
                Accepted : ${data.summary.offer.stats.accept}<br>
                Rejected : ${data.summary.offer.stats.reject}<br>
                Pending : ${data.summary.offer.stats.open}?>
            </div>
        </div>
        <div class="valueblock">
            <label># of tasks per creator</label>
            <div class="content">
                ${data.summary.task.length}
            </div>
        </div>
        <div class="valueblock">
            <label># of Submissions</label>
            <div class="content">
                Expect : ${data.summary.submission.stats.expect}<br>
                Accepted: ${data.summary.submission.stats.accept}<br>
                Rejected: ${data.summary.submission.stats.reject}<br>
                Pending: ${data.summary.submission.stats.pending}
            </div>
        </div>
        <div class="valueblock">
            <label># of Port Report</label>
            <div class="content">
                Expect : ${data.summary.post_report.stats.expect}<br>
                Accepted: ${data.summary.post_report.stats.accept}<br>
                Rejected: ${data.summary.post_report.stats.reject}<br>
                Pending: ${data.summary.post_report.stats.pending}
            </div>
        </div>
        <div class="valueblock">
            <label>Description</label>
            <div class="content">
                <div class="ql-snow">
                    <div class="linkify ql-editor">
                        ${data.description}
                    </div>
                </div>        
            </div>
        </div>
    </div>
`;