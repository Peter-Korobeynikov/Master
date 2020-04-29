import { Tygh } from '../..';
import { ObjectStorage } from './objectStorage';
import { BaseMultipleDecorator } from "./decorators/selection/baseMultipleDecorator";
import { ExternalContainerDecorator } from "./decorators/selection/externalContainerDecorator";
import { PredefinedVariantsDecorator } from "./decorators/data/predefinedVariantsDecorator";
import $ from "jquery";

const _ = Tygh;

export class ObjectPicker {
    constructor($elem, options) {
        this.$elem = $elem;
        this.options = options;

        this.tryLoadFromStorage();

        let select2 = this.$elem.select2(this.buildSelect2Options());

        if (this.options.allowSorting) {
            this.$elem.select2Sortable();
        }

        if (this.options.autofocus) {
            this.$elem.select2('focus');
        }

        if (this.options.autoopen) {
            this.openDropdown();
        }

        this.bindEvents();
        this.fireEvent('inited');
    }

    destroy() {
        this.$elem.select2('destroy');
    }

    resize() {
        let $select2Wrapper = this.$elem.parent();

        if ($select2Wrapper.hasClass('select2-wrapper--width-auto')) {
            return;
        }

        $select2Wrapper
            .find('input.select2-search__field, .select2-container')
            .css({ width: '100%' });

        this.fireEvent('resized');
    }

    getSelectedObjectIds() {
        let ids = this.$elem.val();

        if (!Array.isArray(ids)) {
            ids = [ids];
        }

        return ids;
    }

    setSelectedObjectIds(value) {
        this.$elem.val(value);
        this.$elem.trigger('change');
    }

    selectObjectId(value) {
        if (this.isMultiple()) {
            let ids = new Set(this.getSelectedObjectIds());
            ids.add(value);

            value = Array.from(ids);
        }

        this.setSelectedObjectIds(value);
    }

    unselectObjectId(value) {
        if (this.isMultiple()) {
            let ids = new Set(this.getSelectedObjectIds());
            ids.delete(String(value));

            value = Array.from(ids);
        } else {
            value = null;
        }

        this.setSelectedObjectIds(value);
    }

    addObjects(objects, selected = true, load = true) {
        let self = this,
            objectIds = new Set();

        objects.forEach(function (object) {
            let $option = self.$elem.find(`option[value="${object.id}"]`);

            if (!$option.length) {
                let option = new Option(object.text, object.id, selected, selected);
                self.$elem.append(option);
            } else if (selected) {
                $option.prop('selected', true);
            }

            objectIds.add(object.id);
        });

        this.$elem.trigger('change');

        if (this.isAjaxSource() && load) {
            ObjectPicker.loadObjects($([this.$elem]), this.options.objectType, objectIds);
        }
    }

    updateObjects(objects) {
        let self = this,
            select2 = this.$elem.data('select2');

        objects.forEach(function (object) {
            let $option = self.$elem.find(`option[value="${object.id}"]`),
                currentData = $option.data('data') || {},
                data = $.extend({}, currentData, object);

            currentData.isChanged = data.isChanged = false;
            data.isChanged = JSON.stringify(currentData) !== JSON.stringify(data);

            $option.text(object.text);
            $option.data('data', data);
            $option.removeAttr('data-select2-id');
        });

        if (select2) {
            select2.dataAdapter.current(function (data) {
                select2.trigger('selection:update', {
                    data: data
                });
            });
        }
    }

    openDropdown() {
        this.$elem.select2('open');
    }

    closeDropdown() {
        this.$elem.select2('close');
    }

    isMultiple() {
        return this.$elem.is('[multiple]');
    }

    isCreateObjectAvailable() {
        return Boolean(this.options.enableCreateObject);
    }

    isAjaxSource() {
        return Boolean(this.options.ajaxUrl);
    }

    getObjectType() {
        return this.options.objectType;
    }

    isInited() {
        return this.$elem.data('caObjectPickerInited') === true;
    }

    isDropdownOpen() {
        return this.$elem.data('select2').isOpen();
    }

    fireEvent(event, ...params) {
        this.$elem.trigger(`ce:object_picker:${event}`, [this, ...params]);
        $.ceEvent('trigger', `ce.object_picker.${event}`, [this, ...params]);
    }

    buildSelect2Options() {
        let self = this;
        let options = {
            width: this.options.width,
            allowClear: this.options.allowClear,
            closeOnSelect: this.options.closeOnSelect,
            containerCssClass: this.options.containerCssClass,
            dropdownCssClass: this.options.dropdownCssClass,
            language: {
                loadingMore: function () {
                    return _.tr(self.options.languageLoadingMore);
                },
                searching: function () {
                    return _.tr(self.options.languageSearching);
                },
                errorLoading: function () {
                    return _.tr(self.options.languageErrorLoading);
                },
                noResults: function () {
                    return _.tr(self.options.languageNoResults);
                }
            },
            maximumInputLength: this.options.maximumInputLength,
            maximumSelectionLength: this.options.maximumSelectionLength,
            minimumInputLength: this.options.minimumInputLength,
            minimumResultsForSearch: this.options.enableSearch ? this.options.minimumResultsForSearch : Infinity,
            externalContainerSelector: this.options.externalContainerSelector,
            placeholder: {
                id: this.options.placeholderValue,
                text: this.options.placeholder,
                loaded: true,
                data: {
                    name: this.options.placeholder,
                },
            },
            selectOnClose: this.options.selectOnClose,
            templateResult: function (object) {
                return self.renderResultItemTemplate(object);
            },
            templateSelection: function (object) {                
                return self.renderSelectionItemTemplate(object);
            },
            predefinedVariants: this.options.predefinedVariants
        };

        if (this.options.dropdownParentSelector) {
            options.dropdownParent = $(this.options.dropdownParentSelector);
        }

        if (this.isAjaxSource()) {
            options.ajax = {
                url: this.options.ajaxUrl,
                delay: this.options.ajaxDelay,
                data: function (params) {
                    let request = {
                        q: params.term,
                        page: params.page || 1,
                        page_size: self.options.ajaxPageSize
                    };

                    return request;
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    data.objects.forEach(function (object) {
                        object.loaded = true;
                    });

                    return {
                        results: data.objects,
                        pagination: {
                            more: (params.page * self.options.ajaxPageSize) < data.total_objects
                        }
                    };
                },
                transport: function (params, success, failure) {
                    ObjectStorage.find(params.url, self.options.objectType, params)
                        .done(success)
                        .fail(failure);
                }
            }
        }

        if (this.isCreateObjectAvailable()) {
            options.tags = true;
            options.createTag = function (params) {
                return self.createNewObjectCallback(params);
            }
        }

        if (!this.options.escapeHtml) {
            options.escapeMarkup = function (m) { return m; };
        }

        let Options = $.fn.select2.amd.require('select2/options'),
            Utils = $.fn.select2.amd.require('select2/utils'),
            opts = new Options(options, this.$elem);

        if (this.isMultiple()) {
            let selectionAdapter = opts.get('selectionAdapter');

            selectionAdapter = Utils.Decorate(selectionAdapter, BaseMultipleDecorator);

            if (this.options.externalContainerSelector) {
                selectionAdapter = Utils.Decorate(selectionAdapter, ExternalContainerDecorator);
            }

            opts.set('selectionAdapter', selectionAdapter);
        }

        if (this.options.predefinedVariants.length) {
            let dataAdapter = opts.get('dataAdapter');

            dataAdapter = Utils.Decorate(dataAdapter, PredefinedVariantsDecorator);

            opts.set('dataAdapter', dataAdapter);
        }

        return opts.options;
    }

    renderTemplate(data, template) {
        let templater = new Function('data', `return \`${template}\`;`);

        return templater(data);
    }

    getItemTemplate(selector) {
        return $(selector).html();
    }

    renderResultItemTemplate(object) {
        if (object.loading) {
            return object.text;
        }

        let template;

        object.data = object.data || {};

        if (object.isPredefined && this.options.templateResultPredefinedSelector) {
            template = this.renderTemplate(object.data, this.getItemTemplate(this.options.templateResultPredefinedSelector));
        } else if (object.isNew && this.options.templateResultNewSelector) {
            template = this.renderTemplate(object.data, this.getItemTemplate(this.options.templateResultNewSelector));
        } else if (this.options.templateResultSelector) {
            template = this.renderTemplate(object.data, this.getItemTemplate(this.options.templateResultSelector));
        }

        if (!template) {
            template = object.text;
        }

        this.fireEvent('init_template_result_item', object, template);

        return template;
    }

    renderSelectionItemTemplate(object) {
        let template;

        object.data = object.data || {};

        if (object.isPredefined && this.options.templateSelectionPredefinedSelector) {
            template = this.renderTemplate(object.data, this.getItemTemplate(this.options.templateSelectionPredefinedSelector));
        } else if (object.isNew && this.options.templateSelectionNewSelector) {
            template = this.renderTemplate(object.data, this.getItemTemplate(this.options.templateSelectionNewSelector));
        } else if (this.isAjaxSource() && !object.loaded && this.options.templateSelectionLoadSelector) {
            template = this.renderTemplate(object.data, this.getItemTemplate(this.options.templateSelectionLoadSelector));
        } else if (this.options.templateSelectionSelector) {
            template = this.renderTemplate(object.data, this.getItemTemplate(this.options.templateSelectionSelector));
        }

        if (!template) {
            template = object.text;
        }

        this.fireEvent('init_template_selection_item', object, template);

        return template;
    }

    createNewObjectCallback(params) {
        let term = $.trim(params.term);

        if (term === '') {
            return null;
        }

        let object = {
            id: term,
            text: term,
            isNew: true,
            content: {
                text: term,
            }
        };

        this.fireEvent('create_object', params, object);

        return object;
    }

    bindEvents() {
        let self = this;

        if (this.options.redrawDropdownOnChange) {
            this.$elem.on('select2:select select2:unselect', function () {
                var select2 = $(this).data('select2');

                if (select2.isOpen()) {
                    select2.dropdown._positionDropdown();
                }
            });
        }

        this.$elem.on('select2:select', function (event) {
            let object = event.params.data;

            if (self.options.createdObjectHolderSelector) {
                if (self.options.allowMultipleCreatedObjects) {
                    if (object.isNew) {
                        let $lastNewVariant = $(self.options.createdObjectHolderSelector).last();
                        let $newVariant = $lastNewVariant.val()
                            ? $lastNewVariant.clone()
                            : $lastNewVariant;

                        $newVariant.val(object.id);
                        $newVariant.insertAfter($lastNewVariant);
                    }
                } else if (object.isNew) {
                    $(self.options.createdObjectHolderSelector).val(object.id);
                } else {
                    $(self.options.createdObjectHolderSelector).val(null);
                }
            }

            self.fireEvent('object_selected', object, event);
        });

        this.$elem.on('select2:unselect', function (event) {
            var object = event.params.data;

            if (self.options.createdObjectHolderSelector && object.newTag) {
                var $newVariants = $(self.options.createdObjectHolderSelector);

                if ($newVariants.length > 1) {
                    $newVariants.each(function(i, newVariant) {
                        let $newVariant = $(newVariant);
                        if ($newVariant.val() === object.id) {
                            $newVariant.remove();
                        }
                    });
                } else {
                    $newVariants.val(null);
                }
            }

            self.fireEvent('object_unselected', object, event);
        });

        this.$elem.on('change', function () {
            let Utils = $.fn.select2.amd.require('select2/utils'),
                $options = $(this).find('option:selected'),
                selected = [];

            $options.each(function () {
                selected.push(Utils.GetData(this, 'data'));
            });

            self.fireEvent('change', self.isMultiple() ? selected : selected.shift());
        });

        this.$elem.on('select2:open', function () {
            self.fireEvent('dropdown_opened');
        });

        this.$elem.on('select2:close', function () {
            self.fireEvent('dropdown_closed');
        });

        this.$elem.on('select2:clear', function () {
            self.fireEvent('cleared');
        });

        $.ceEvent('on', 'ce.window.resize', function (event, args) {
            self.resize();
        });

        $.ceEvent('on', 'ce.tab.show', function(event, args) {
            self.resize();
        });

        if (this.options.extendedPickerId) {
            $.ceEvent('on', 'ce.picker_add_js_items', function (picker, items, data) {
                if (self.options.extendedPickerId !== data.root_id) {
                    return;
                }

                let objects = [];

                $.map(items, function (data, id) {
                    if (data instanceof Object) {
                        var text = text[self.options.extendedPickerTextKey];
                    } else {
                        var text = data;
                    }

                    objects.push({
                        id: id,
                        text: text,
                        loaded: !self.isAjaxSource(),
                        extended_picker_data: data,
                        data: {}
                    });
                });

                if (objects.length) {
                    self.addObjects(objects);
                }
            });
        }

        this.$elem.data('select2').on('selection:update', function () {
            self.fireEvent('selection_updated');
        });
    }

    tryLoadFromStorage() {
        if (!this.isAjaxSource()) {
            return;
        }

        let objects = ObjectStorage.mget(this.options.objectType, this.getSelectedObjectIds());

        if (objects.length) {
            this.updateObjects(objects);
        }
    }

    static loadObjects($elems, objectType, objectIds) {
        objectIds = Array.from(objectIds);
        let url = $elems.get(0).data('caObjectPicker').options.ajaxUrl,
            self = this;

        ObjectStorage.load(url, objectType, objectIds).done(function (map) {
            $elems.each(function (key, $elem) {
                if (!$elem.data('caObjectPicker')) {
                    return;
                }

                let picker = $elem.data('caObjectPicker'),
                    selectedIds = picker.getSelectedObjectIds(),
                    objects = [];

                $.each(selectedIds, function (key, id) {
                    if (map[id]) {
                        objects.push(map[id]);
                    }
                });

                if (objects.length) {
                    picker.updateObjects(objects);
                }
            });
        });
    }
}
