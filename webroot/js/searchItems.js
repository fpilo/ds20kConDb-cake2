function init_searchItemsForm(projects, manufacturers, itemtypes) {
    'use strict';



    /** object type to remember visibility and checked status of an select
     * item, including its dependencies on other items
     */
    function ItemSearchOption(id, name, type, parent){
        this.id = parseInt(id);
        this.name = name;
        this.type = type;
        this.parent = parent;
        //Key to find this option in localStorage
        this.key = "ItemSearchOption-" + type + "-" + id;
        //we start without children or requirements
        this.children = [];
        this.requirements = [];
        //If we have a parent it's obviously a requirement
        if(parent != undefined) this.requirements.push([parent]);
        this.reset();
        //Get checked value from localStorage if that exists
        if(typeof(window.localStorage) != 'undefined') {
            var value = window.localStorage.getItem(this.key);
            if(value !== null) this.checked = (value === "true");
        }
    }

    ItemSearchOption.prototype = {
        /** reset an option to it's default state */
        reset: function () {
            //I would like to have item_type and subtype unchecked by default but
            //that interferes with hiding the extended search
            //this.checked = (type!="item_type" && type!="item_subtype");
            this.checked = true;
            this.visible = false;
        },
        /** check if visibility changed by looking at all required options and
         * seeing if at least one of those is visible and checked per group.
         * Update visibility and return whether it changed
         * @return true if visibility changed
         */
        update: function() {
            var old = this.visible;

            //Only update checked if we were visible
            if(old){
                this.checked = selects.selected[this.type].indexOf(this.id)>-1;
            }
            //But always update localStorage value
            if(typeof(window.localStorage) != 'undefined') {
                window.localStorage.setItem(this.key, this.checked);
            }

            //loop over all requirements and check
            this.visible = true;
            var self = this;
            //requirements is an array of arrays, e.g. [[req1], [req2, req3]]
            //which means req1 must be fullfilled and req2 || req3.
            $.each(this.requirements, function(index, requirement_group){
                var vis = false;
                $.each(requirement_group, function(index, requirement){
                    vis |= requirement.is_valid();
                });
                self.visible &= vis;
            });
            //Mark select to need refresh if visibility changed
            if(old!=this.visible) selects.changed(this.type);
            //return whether it changed
            return old!=this.visible;
        },
        /** return true if we are visibile and checked */
        is_valid: function(){
            return this.checked && this.visible;
        },
        /** add requirements from an array of options given an array of ids
         * @param ids to select from list
         * @param list array of options
         */
        add_requirements: function(ids, list){
            //Apparently no required ids, bail
            if(ids == undefined) return;
            //Make sure ids is an array
            ids = [].concat(ids);
            var req = [];
            $.each(list, function(index, option){
                if(ids.indexOf(option.id)>-1){
                    req.push(option);
                }
            });
            this.requirements.push(req);
        }
    };

    /** fill an array with option objects created from a json object describing
     * the options
     * @param list json object containing option definitions
     * @param parent parent option (optional)\
     */
    function fill_options(list, type, parent){
        var options = [];
        $.each(list, function(id, item){
            //create an option
            var option = new ItemSearchOption(id, item.n, type, parent);
            //Check if we depend on projects and add requirements
            option.add_requirements(item.p, project_options);
            //Check if we depend on manufacturers and add requirements
            option.add_requirements(item.m, manufacturer_options);
            //If subtypes or subtype_versions are defined than add them as
            //children
            if(item.s != undefined){
                option.children = fill_options(item.s, "item_subtype", option);
            }else if(item.v != undefined){
                option.children = fill_options(item.v, "item_subtype_version", option);
            }
            options.push(option);
        });
        return options;
    }

    /** Container class to manage the select boxes */
    function SelectBoxes() {
        //the different selects we care about
        this.types = ["project", "manufacturer", "item_type", "item_subtype", "item_subtype_version"];
        //some selects have direct children in other selects so we need to know which to use
        this.children = {item_type: "item_subtype", item_subtype:"item_subtype_version"};
        //the jquery objects for the select elements
        this.$elements = {};
        //the selected values in each select
        this.selected = {};
        //which selects need an update?
        this.refresh = {};

        //fill elements and selected
        var self = this;
        $.each(this.types, function(index, type){
            self.$elements[type] = $("#" + type + "_id");
            self.selected[type] = [];
        });
    }
    SelectBoxes.prototype = {
        /** get the selected values from all selects and store them in this.selected */
        get_selected: function() {
            for(var type in this.$elements){
                var selected = this.$elements[type].multipleSelect("getSelects");
                //var selected = this.$elements[type].val() || [];
                //convert to ints
                this.selected[type] = $.map(selected, function(e){ return parseInt(e); });
            }
        },
        /** mark a select as changed which means we have to rebuild it once the
         * update is complete */
        changed: function(type){
            this.refresh[type] = true;
        },
        /** clear the elements from all selects */
        empty: function(){
            $.each(this.$elements, function(index, e){ e.empty(); });
        },
        /** update all selects.
         * @param force if true, update them independent on whether they changed
         */
        update: function(force){
            for(var type in this.$elements){
                if(force || this.refresh[type]) {
                    this.$elements[type].multipleSelect("refresh");
                }
            }
            //since we just updated them we can reset the refresh list
            this.refresh = {};
        },
        /** get the select object for a given type */
        get: function(type){
            return this.$elements[type];
        },
        /** get the select object for children of a given type */
        get_child: function(type){
            return this.$elements[this.children[type]];
        },
    };

    //populate the option lists
    /**
     * Function to sort by name attribute of object
     */
    function SortByName(a,b){
	  var aName = a.name.toLowerCase();
	  var bName = b.name.toLowerCase();
	  return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
	}

    var project_options = fill_options(projects, "project");
    var manufacturer_options = fill_options(manufacturers, "manufacturer");
    var itemtype_options = fill_options(itemtypes, "item_type");

    project_options.sort(SortByName);
    manufacturer_options.sort(SortByName);
    itemtype_options.sort(SortByName);
    //Create SelectBox instance
    var selects = new SelectBoxes();

    /** update a list of options and add them to the correct select element */
    function update_options(options, $parent, reset){
        $.each(options, function(index, option){
            //update the option (unless not wanted for initial population of selects) */
            if(reset === true) option.reset();
            option.update();
            if(option.visible){
                //Ok, it's visible, add it
                var $option = $("<option/>", {value:option.id, text:option.name, selected:option.checked});
                $parent.append($option);
            }
            //There are children, create an optgroup and add them all
            if(option.children.length>0){
                var name = option.name;
                if(option.parent != undefined) { name += " (" + option.parent.name + ")"; }
                var $group = $("<optgroup/>", {label:name});
                update_options(option.children, $group, reset);
                //optgroup is non-empty so add it to the correct select for children of this type
                if($group.children().length>0) selects.get_child(option.type).append($group);
            }
        });
    }

    //Ok, initial population of our select statements
    update_options(project_options, selects.get("project"));
    update_options(manufacturer_options, selects.get("manufacturer"));
    update_options(itemtype_options, selects.get("item_type"));

    //to avoid running the onclick event during updates
    var update_running = false;
    function onclick(){
        //already doing update, ignore
        if(update_running) return;
        update_running = true;
        //get selected elements
        selects.get_selected();
        //clear selects
        selects.empty();
        //update all options
        update_options(project_options, selects.get("project"));
        update_options(manufacturer_options, selects.get("manufacturer"));
        update_options(itemtype_options, selects.get("item_type"));
        //update select elements if they changed
        selects.update();
        //done
        update_running = false;
    }

    //reset all fields to be checked
    function reset(){
        //disable onclick() handling
        update_running = true;
        //clear selects
        selects.empty();
        //reset all options
        update_options(project_options, selects.get("project"), true);
        update_options(manufacturer_options, selects.get("manufacturer"), true);
        update_options(itemtype_options, selects.get("item_type"), true);
        //update all selects
        selects.update();
        //reenable onclick() handling
        update_running = false;
    }


    //Create multipleSelect instances
    $("#searchDIV select.multiple").each(function(index) {
        var $select = $(this);
        //Keep the selection items open
        var keepOpen = $.inArray(this.id, ["item_type_id", "item_subtype_id", "item_subtype_version_id"])>-1;
        $select.multipleSelect({
            //Text to show if nothing is selected
            placeholder: "No selection",
            //Width is controlled by container so set to full width
            width: "100%",
            //If more than 5 items are selected show only "n of m selected" but
            //if the number of entries is less than 5 we still want "All
            //selected" if all are selected
            minimumCountSelected: Math.min(5, $select.children().length),
            //Show a search box for more than 10 entries or for the item* selects
            filter: $select.children().length>10 || keepOpen,
            //keep the item* selects always open
            isOpen: keepOpen,
            keepOpen: keepOpen,
            //hide checkboxes for optgroups as they just make everything noisy
            //and don't serve much of a purpose
            hideOptgroupCheckboxes: true,
            //For item_subtype_versions we like to show more than one item next
            //to each other to save space
            //multiple: (this.id == "item_subtype_version_id"),
            multipleWidth: 60,
            //And use normal parentheses for the select all button
            selectAllDelimiter: ["(", ")"],
        });
        //Add Event listeners to all select after creation is done because
        //we might access the select in the update before all are created
        //otherwise. Also we can reuse the constructor and only change the event assignment
        var opt = $select.data("multipleSelect").options;
        var id = this.id;
        if($.inArray(this.id, ["project_id", "manufacturer_id"])>-1){
            opt.onClick = onclick;
            opt.onCheckAll = onclick;
            opt.onUncheckAll = onclick;
        }else if(keepOpen){
            opt.onClick = onclick;
            opt.onCheckAll = onclick;
            opt.onUncheckAll = onclick;
            //disable closing of these selects because we want them always open
            $select.data("multipleSelect").close = function() {};
        }
    });
    //Return the reset function to be used by the form
    return reset;
}

