'use strict';

$(document).ready(function () {
    $('.translit-generate').on('click', function () {
        let valueToTranslit = $('.translit-input').val();
        let translitedValue = urlLit(valueToTranslit, 0);
        $('.translit-output').val(translitedValue);
    })
});

function urlLit(w, v) {
    let tr = 'a b v g d e ["zh","j"] z i y k l m n o p r s t u f h c ch sh ["shh","shch"] ~ y ~ e yu ya ~ ["jo","e"]'.split(' ');
    let ww = '';
    w = w.toLowerCase();
    for (let i = 0; i < w.length; ++i) {
        let cc = w.charCodeAt(i);
        let ch = (cc >= 1072 ? tr[cc - 1072] : w[i]);
        if (ch.length < 3) ww += ch; else ww += eval(ch)[v];
    }
    return (ww.replace(/[^a-zA-Z0-9\-]/g, '-').replace(/[-]{2,}/gim, '-').replace(/^\-+/g, '').replace(/\-+$/g, ''));
}
