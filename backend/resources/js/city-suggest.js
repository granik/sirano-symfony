$(function () {
    var $city = $('#' + cityInputId);

    // удаляет районы города и всё с 65 уровня
    function removeNonCity(suggestions) {
        return suggestions.filter(function (suggestion) {
            return suggestion.data.city_district === null && suggestion.data.fias_level !== '65';
        });
    }

    function join(arr /*, separator */) {
        var separator = arguments.length > 1 ? arguments[1] : ', ';
        return arr.filter(function (n) {
            return n
        }).join(separator);
    }

    function cityToString(address) {
        return join([
            join([address.city_type, address.city], ' '),
            join([address.settlement_type, address.settlement], ' ')
        ]);
    }

    function selectCity(suggestion) {
        var name;
        var fullName = '';

        if (suggestion.data.settlement) {
            name = suggestion.data.settlement_with_type;
        } else {
            if (suggestion.data.city_type !== 'г') {
                name = suggestion.data.city_with_type;
            } else {
                name = suggestion.data.city;
            }
        }

        if (suggestion.data.region_type !== 'г') {
            fullName = suggestion.data.region + ' ' + suggestion.data.region_type_full + ', ';
        }

        if (suggestion.data.area) {
            fullName = fullName + suggestion.data.area + ' ' + suggestion.data.area_type_full + ', ';
        }

        if (suggestion.data.city_district) {
            fullName = fullName + suggestion.data.city_district + ' ' + suggestion.data.city_district_type_full + ', ';
        }

        fullName = fullName + name;

        $('input#' + formPrefix + '_kladrId').val(suggestion.data.kladr_id);
        $('input#' + formPrefix + '_country').val(suggestion.data.country);
        $('input#' + formPrefix + '_cityName').val(name);
        $('input#' + formPrefix + '_fullCityName').val(fullName);
        $city.val(name);
    }

    function clearCurrentCity() {
        $('input#' + formPrefix + '_kladrId').val(null);
        $('input#' + formPrefix + '_country').val(null);
        $('input#' + formPrefix + '_fullCityName').val(null);
    }

    // Ограничиваем область поиска от города до населенного пункта
    $city.suggestions({
        token: dadataToken,
        type: 'ADDRESS',
        hint: false,
        bounds: 'city-settlement',
        onSuggestionsFetch: removeNonCity,
        onSelect: selectCity,
        onSelectNothing: function (query) {
            clearCurrentCity();
        },
        onInvalidateSelection: function (suggestion) {
            clearCurrentCity();
        }
    });

    // Определяем город по IP-адресу
    if (dadataGeoLocation) { // Только при регистрации
        if (typeof $city.suggestions().getGeoLocation() !== 'undefined') {
            $city.suggestions().getGeoLocation().done(function (locationData) {
                var sgt = {
                    value: null,
                    date: locationData
                };
                $city.suggestions().setSuggestion(sgt);
                $city.val(cityToString(locationData));
            });
        }
    }

    // Переключение на иностранное государство
    $('#dadata-foreign-city').on('change', function() {
        if (this.checked) {
            $city.suggestions().setOptions({
                constraints: {
                    locations: { country: "*" }
                }
            });
        } else {
            $city.suggestions().setOptions({
                constraints: {
                    locations: {}
                }
            });
        }
    });

    $('#' + formPrefix + '_foreignCity').on('change', function() {
        if (this.checked) {
            $city.suggestions().setOptions({
                constraints: {
                    locations: { country: "*" }
                }
            });
        } else {
            $city.suggestions().setOptions({
                constraints: {
                    locations: {}
                }
            });
        }
    });
});