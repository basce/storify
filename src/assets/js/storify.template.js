var storify = storify || {};
storify.template = storify.template || {};

// project detail

// project listing
storify.template.createListItem = (data, project_id, actions) => `
        <div class="project-item" id="${project_id}">
            ${
                (before_time_left=>{
                    if(before_time_left){
                        return `
		    <div class="ribbon-featured">
		        <div class="ribbon-start"></div>
		        <div class="ribbon-content">Due Soon</div>
		        <div class="ribbon-end">
		            <figure class="ribbon-shadow"></figure>
		        </div>
		    </div>
                        `;
                    }else{
                    	return ``;
                    }
                })(data.duesoon)
            }
            <div class="wrapper">
                <div class="image" ${
                    (display_image=>{
                        if(display_image){
                            return `style="background-image:url(${display_image});"`;
                        }else{
                        	return ``;
                        }
                    })(data.summary.display_image)
                }>
                    <div class="tags">${
                        (brands=>{
                            if(brands.length){
                                return brands.map(brand=>{
                                    if(brand.term_id){
                                        return `<span data-term_id="${brand.term_id}">${brand.name}</span>`;
                                    }else{
                                        return ``;
                                    }
                                }).join("");
                            }else{
                                return ``;
                            }
                        })(data.summary.brand)
                    }</div>
                    <div title="${
                        (bounty=>{
                            if(bounty.length == 2){
                                return `$${storify.project.formatMoney(bounty[0].value)} & ${bounty[1].value}`;
                            }else{
                                if(bounty[0].type == "cash"){
                                    return `$${storify.project.formatMoney(bounty[0].value)}`;
                                }else{
                                    return `$${bounty[0].value}`;
                                }
                            }
                        })(data.summary.bounty)
                    }" class="price">${
                        (bounty=>{
                            if(bounty.length == 2){
                                return `<i class="fa fa-money" aria-hidden="true"></i> & <i class="fa fa-gift" aria-hidden="true"></i>`;
                            }else{
                                if(bounty[0].type == "cash"){
                                    return `<i class="fa fa-money" aria-hidden="true"></i>`;
                                }else{
                                    return `<i class="fa fa-gift" aria-hidden="true"></i>`;
                                }
                            }
                        })(data.summary.bounty)
                    }</div>
                </div>
                <div class="content">
                    <h3>
                        <div class="meta">
                            <figure>
                                <i class="fa fa-calendar-o"></i> Created ${data.summary.formatted_created_date2}
                            </figure>
                            <figure>
                                <i class="fa fa-calendar-o"></i> Accept ${data.summary.formatted_invitation_closing_date2}
                            </figure>
                            <figure>
                                <i class="fa fa-calendar-o"></i> Deliver ${data.summary.formatted_closing_date2}
                            </figure>
                        </div>
                        ${data.name}
                        ${
                            (tags=>{
                                if(tags.length){
                                    return tags.map(tag=>{
                                        return `<span class="tag" data-term_id="${tag.term_id}">${tag.name}</span>`;
                                    }).join("");
                                }else{
                                    return ``;
                                }
                            })(data.summary.tag)
                        }
                    </h3>
                    ${
                        (locations=>{
                            if(locations.length){
                                var templocations = locations.map(location=>{
                                    return `<span data-term_id="${location.term_id}">${location.name}</span>`;
                                }).join(", ");
                                return `<h4 class="location">${templocations}</h4>`;
                            }else{
                                return ``;
                            }
                        })(data.summary.location)
                    }
                    <div class="description ${
                    	(actions=>{
                    		if(actions.length == 1){
                    			return `one-button`;
                    		}else if(actions.length == 2){
                    			return `two-button`;
                    		}else{
                    			return `three-button`;
                    		}
                    	})(actions)
                    }">
                        <p class="linkify">
                            ${
                                (deliverables=>{
                                    return deliverables.map( (deliverable, index)=>{
                                        return `${deliverable.amount} ${deliverable.type == "photo"?`<i class="fa fa-camera" aria-hidden="true"></i>`:`<i class="fa fa-video-camera" aria-hidden="true"></i>`}`;
                                    }).join(" | ");
                                })(data.summary.deliverables_ar)
                            }
                            <br>
                            ${data.summary.description}
                        </p>
                    </div>
                    <div class="actions">
                    	${
                    		(actions=>{
                    			if(actions.length){
                    				return actions.map(action=>{
                    					return `<a href="#${project_id}" class="${action.classname}">${action.label}</a>`;
                    				}).join("");
                    			}else{
                    				return ``;
                    			}
                    		})(actions)
                    	}
                    </div>
                </div>
            </div>
        </div>
    `;

storify.template.createListItemBrandView = (data, project_id, actions) => `
        <div class="project-item" id="${project_id}">
            ${
                (before_time_left=>{
                    if(before_time_left){
                        return `
            <div class="ribbon-featured">
                <div class="ribbon-start"></div>
                <div class="ribbon-content">Due Soon</div>
                <div class="ribbon-end">
                    <figure class="ribbon-shadow"></figure>
                </div>
            </div>
                        `;
                    }else{
                        return ``;
                    }
                })(data.duesoon)
            }
            <div class="wrapper">
                <div class="image" ${
                    (display_image=>{
                        if(display_image){
                            return `style="background-image:url(${display_image});"`;
                        }else{
                            return ``;
                        }
                    })(data.summary.display_image)
                }>
                    <div class="tags">${
                        (brands=>{
                            if(brands.length){
                                return brands.map(brand=>{
                                    if(brand.term_id){
                                        return `<span data-term_id="${brand.term_id}">${brand.name}</span>`;
                                    }else{
                                        return ``;
                                    }
                                }).join("");
                            }else{
                                return ``;
                            }
                        })(data.summary.brand)
                    }</div>
                    <div title="${
                        (bounty=>{
                            if(bounty.length == 2){
                                return `$${storify.project.formatMoney(bounty[0].value)} & ${bounty[1].value}`;
                            }else{
                                if(bounty[0].type == "cash"){
                                    return `$${storify.project.formatMoney(bounty[0].value)}`;
                                }else{
                                    return `$${bounty[0].value}`;
                                }
                            }
                        })(data.summary.bounty)
                    }" class="price">${
                        (bounty=>{
                            if(bounty.length == 2){
                                return `<i class="fa fa-money" aria-hidden="true"></i> & <i class="fa fa-gift" aria-hidden="true"></i>`;
                            }else{
                                if(bounty[0].type == "cash"){
                                    return `<i class="fa fa-money" aria-hidden="true"></i>`;
                                }else{
                                    return `<i class="fa fa-gift" aria-hidden="true"></i>`;
                                }
                            }
                        })(data.summary.bounty)
                    }</div>
                </div>
                <div class="content">
                    <h3>
                        <div class="meta">
                            <figure>
                                <i class="fa fa-calendar-o"></i> Created ${data.summary.formatted_created_date2}
                            </figure>
                            <figure>
                                <i class="fa fa-calendar-o"></i> Accept ${data.summary.formatted_invitation_closing_date2}
                            </figure>
                            <figure>
                                <i class="fa fa-calendar-o"></i> Deliver ${data.summary.formatted_closing_date2}
                            </figure>
                        </div>
                        ${data.name}
                        ${
                            (tags=>{
                                if(tags.length){
                                    return tags.map(tag=>{
                                        return `<span class="tag" data-term_id="${tag.term_id}">${tag.name}</span>`;
                                    }).join("");
                                }else{
                                    return ``;
                                }
                            })(data.summary.tag)
                        }
                    </h3>
                    ${
                        (locations=>{
                            if(locations.length){
                                var templocations = locations.map(location=>{
                                    return `<span data-term_id="${location.term_id}">${location.name}</span>`;
                                }).join(", ");
                                return `<h4 class="location">${templocations}</h4>`;
                            }else{
                                return ``;
                            }
                        })(data.summary.location)
                    }
                    <div class="description ${
                        (actions=>{
                            if(actions.length == 1){
                                return `one-button`;
                            }else if(actions.length == 2){
                                return `two-button`;
                            }else{
                                return `three-button`;
                            }
                        })(actions)
                    }">
                        <p class="linkify">
                            ${
                                (deliverables=>{
                                    return deliverables.map( (deliverable, index)=>{
                                        return `${deliverable.amount} ${deliverable.type == "photo"?`<i class="fa fa-camera" aria-hidden="true"></i>`:`<i class="fa fa-video-camera" aria-hidden="true"></i>`}`;
                                    }).join(" | ");
                                })(data.summary.deliverables_ar)
                            }
                            / Creator
                            <br>
                            ${data.summary.description}
                        </p>
                    </div>
                    <div class="actions">
                        ${
                            (actions=>{
                                if(actions.length){
                                    return actions.map(action=>{
                                        return `<a href="#${project_id}" class="${action.classname}">${action.label}</a>`;
                                    }).join("");
                                }else{
                                    return ``;
                                }
                            })(actions)
                        }
                    </div>
                </div>
            </div>
        </div>
    `;

storify.template.simpleModal = (data, modal_id, actions) => `
    <modal class="modal show" tabindex="-1" role="dialog" id="${modal_id}">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${data.titlehtml}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    ${data.bodyhtml}
                </div>
                <div class="modal-footer">
                    <div class="actions">
                    ${
                            (actions=>{
                                if(actions.length){
                                    return actions.map(action=>{
                                        if(action.attr){
                                            var button = `<a `+ Object.entries(action.attr).map(attr=>{return attr[0]+ `="` + attr[1] + `"`}).join(" ") + ` >`+action.label+`</a>`;
                                            return button;
                                        }else{
                                            return ``;
                                        }
                                    }).join(" ");
                                }else{
                                    return ``;
                                }
                            })(actions)
                        }
                    </div>
                </div>
            </div>
        </div>
    </modal>
`;

storify.template.createProjectListItem = (data, project_id, actions) =>`
    <div class="project-item" id="${project_id}">
        <div class="ribbon-featured">
            <div class="ribbon-start"></div>
            <div class="ribbon-content">New</div>
            <div class="ribbon-end">
                <figure class="ribbon-shadow"></figure>
            </div>
        </div>
        <div class="wrapper">
            <div class="content">
                <h3>${data.title}</h3>
                <p>
                ${
                    (locations=>{
                        if(locations.length){
                            var templocations = locations.map(location=>{
                                return `<span data-term_id="${location.term_id}">${location.name}</span>`;
                            }).join(", ");
                            return templocations;
                        }else{
                            return ``;
                        }
                    })(data.summary.location)
                }
                </p>
                <p>
                ${
                    (brands=>{
                        if(brands.length){
                            var tempbrands = brands.map(brand=>{
                                return `<span data-term_id="${brand.term_id}">${brand.name}</span>`;
                            }).join(", ");
                            return tempbrands;
                        }else{
                            return ``;
                        }
                    })(data.summary.brand)
                }
                </p>
                <p>
                ${
                    (tags=>{
                        if(tags.length){
                            var temptags = tags.map(tag=>{
                                return `<span data-term_id="${tag.term_id}">${tag.name}</span>`;
                            }).join(", ");
                            return temptags;
                        }else{
                            return ``;
                        }
                    })(data.summary.tag)
                }
                </p>
                <p>
                    ${data.summary.description}
                </p>
                <p># of creators: ${data.summary.offer.data.length}</p>
                <div class="creators">
                    ${
                        (creators=>{
                            var creator_div = creators.map(creator=>{
                                return `<span class="creator ${creator.status}" title="${creator.igusername}" style="background-image:url(${creator.profile_image})"></span>`;
                            }).join(" ");
                            return creator_div;
                        })(data.summary.offer.data)
                    }
                </div>
                <div>
                    ${
                        (tasks=>{
                            return tasks.length == 1 ? `1 task` : tasks.length + " tasks";
                        })(data.summary.task)
                    } / Creator
                </div>
                <div>
                    ${
                        (submissions=>{
                            return submissions.stats.pending == 1 ? `1 Submission` : submissions.stats.pending + " Submissions";
                        })(data.summary.submission)
                    } waiting for review
                    <br>
                    ${
                        (post_reports=>{
                            return post_reports.stats.pending == 1 ? `1 Performance Report` : post_reports.stats.pending + " Performance Reports";
                        })(data.summary.post_report)
                    } waiting for review
                </div>
                <div class="actions">
                    ${
                        (actions=>{
                            console.log(actions);
                            if(actions.length){
                                return actions.map(action=>{
                                    if(action.attr){
                                        var button = `<a `+ Object.entries(action.attr).map(attr=>{return attr[0]+ `="` + attr[1] + `"`}).join(" ") + ` >`+action.label+`</a>`;
                                        return button;
                                    }else{
                                        return ``;
                                    }
                                }).join(" ");
                            }else{
                                return ``;
                            }
                        })(actions)
                    }
                </div>
            </div>
        </div>
    </div>
`;