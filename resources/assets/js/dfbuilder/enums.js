/**
 * Extend helper for done typing
 */

var notyPositons = {
    topRight:"topRight",
    top:"top",
    topLeft:"topLeft",
    bottomLeft:"bottomLeft",
    bottomRight:"bottomRight"
};


var notyMessageTypes = {
    success:"success",
    error:"error",
    warning:"warning",
    information:"information",
    alert:"alert"
};

var conditionValueRules = {
    contains:1,
    not_contains:2,
    is_equal_to:4,
    is_not_equal_to:3,
    is_empty:7,
    not_empty:8,
    contains_multi:9,
    not_contains_multi:10,
    equals_multi:11,
    not_equals_multi:12,
    gt:13,
    gte:14,
    lt:15,
    lte:16,
    range:17
};