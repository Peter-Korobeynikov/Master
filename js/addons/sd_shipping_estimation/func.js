(function (_, $) {

    $(document).ready(function () {

        ({
            init: function (){

                var self = this,
                    shippingData = $('div[id^=shipping_methods_list]');

                shippingData.each(function () {

                    var currentBlock = $(this),
                        currentBlockData = currentBlock.data(),
                        calculateButton = $('#get_shipping_methods_list_' + currentBlockData.caBlockId),
                        inscriptionCity = $('div[id=shipping_methods_inscription_' + currentBlockData.caBlockId + ']');

                    if (currentBlockData.caFilterCrawlers === 'Y') {
                        self.checkForPrevent().then(function(shouldPreventRequest) {

                            if (shouldPreventRequest) {
                                currentBlock.find('.shipping_loading').remove();
                            } else {
                                self.buildShippingBlock(currentBlockData, calculateButton, inscriptionCity);
                            }
                        });
                    } else {
                        self.buildShippingBlock(currentBlockData, calculateButton, inscriptionCity);
                    }
                });
            },

            /**
             * Builds content of the add-on and launches calculation of the shipping cost
             *
             * @param {Object} currentBlockData
             * @param {jQuery} calculateButton
             * @param {jQuery} inscriptionCity
             */
            buildShippingBlock: function (currentBlockData, calculateButton, inscriptionCity) {

                var self = this,
                    block;

                if (currentBlockData.caSdShippingEstimation === 'Y'
                    && (currentBlockData.caAutoLoad == 'Y'
                        || currentBlockData.caBlockType !== 'shipping_estimation')
                ) {
                    inscriptionCity.show();

                    self.calculateShipping({
                        blockData: currentBlockData
                    });
                } else if (currentBlockData.caShowInPopup === 'Y') {
                    $.ceEvent('on', 'ce.dialogshow', function(context) {
                        block = context.find('div[id^="shipping_methods_list_' + currentBlockData.caBlockId + '"]');
                        if (block.length > 0 && !block.data('init_shipping')) {
                            self.calculateShipping({
                                blockData: currentBlockData
                            });
                        }
                    });
                } else {
                    calculateButton.show();
                    inscriptionCity.show();
                    calculateButton.on('click', function (){
                        self.calculateShipping({
                            blockData: currentBlockData,
                            hidden: false
                        });
                    });
                }
                addLinkToChangeMaxMindCity(currentBlockData.caBlockId);
            },

            /**
             * Calculating shipping method
             *
             * @param {Object}  data
             * @param {Object}  data.blockData
             * @param {boolean} data.hidden
             */
            calculateShipping: function (data) {

                var url = fn_url('shipping_estimation.get_shipping_methods_list');

                if (data.hidden === undefined) {
                    data.hidden = true;
                }

                $.ceAjax('request', url, {
                    method: 'post',
                    result_ids: 'shipping_methods_list_' + data.blockData.caBlockId,
                    hidden: data.hidden,
                    caching: true,
                    data: {
                        product_id: data.blockData.caProductId,
                        block_id: data.blockData.caBlockId,
                        block_type: data.blockData.caBlockType
                    },
                    callback: function () {
                        $('#get_shipping_methods_list_' + data.blockData.caBlockId).hide();
                        $('div[id=shipping_methods_inscription_' + data.blockData.caBlockId + ']').show();
                        $('div[id^="shipping_methods_list_' + data.blockData.caBlockId + '"]').data('init_shipping', true);

                        addLinkToChangeMaxMindCity(data.blockData.caBlockId);
                    }
                });
            },

            /**
             * Checking for necessity to prevent requests and display the add-on content
             *
             * @returns Promise
             */
            checkForPrevent: function () {
                var url = fn_url('shipping_estimation.should_prevent_request');

                return new Promise(function(resolve, reject) {
                    $.ceAjax('request', url, {
                        method: 'post',
                        skip_result_ids_check: true,
                        callback: function (responseData) {
                            var responseJson = JSON.parse(responseData.text);

                            if (typeof responseJson.shouldPreventRequest !== 'undefined') {
                                resolve(responseJson.shouldPreventRequest);
                            } else reject(responseJson.shouldPreventRequest);
                        }
                    });
                });
            },

        }).init();

    });
    /**
    * Added link to change city from MaxMind block
    *
    * @param int  block id
    */
    function addLinkToChangeMaxMindCity (blockId) {
        if ($("div[id*='sd_maxmind_city_']").length > 0) {
            var linkId = $("div[id*='sd_maxmind_city_']").find("a[id*='opener_gmm_content_for_popup_']");
            $("span[id='shipping_methods_inscription_change_location_" + blockId + "']").wrap("<a class='cm-external-click' data-ca-external-click-id=" + $(linkId).attr('id') + "></a>");
        }
    };

}(Tygh, Tygh.$));