/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'ru';
     config.contentsCss = ['/asset/css/reset.css','/asset/css/general.css','/asset/css/style.css'];
     config.bodyClass = 'w960 main-bg box-shadow';
     config.extraPlugins = 'youtube';
     config.allowedContent = true;     
};

CKEDITOR.stylesSet.add( 'default', [
    { name : 'Отступ 20px слева и справа'       , element : 'div', attributes: { 'class': 'pad20-lr' } },
    { name : 'Оглавление 1'                       , element : 'span', attributes : { 'class':'fq-head' } },
    { name : 'Оглавление 2'                       , element : 'span', attributes : { 'class':'fq-form-name' } },
    { name : 'Цвет 1'                           , element : 'span', attributes : { 'class':'color-red' } },
    { name : 'Цвет 2'                           , element : 'span', attributes : { 'class':'color-activeLink' } },
    { name : 'Цвет 3'                           , element : 'span', attributes : { 'class':'color-orange' } },
    { name : 'Интервал 80%'                     , element : 'div', styles : { 'line-height':'80%' } },
    { name : 'Интервал 100%'                    , element : 'div', styles : { 'line-height':'100%' } },
    { name : 'Интервал 150%'                    , element : 'div', styles : { 'line-height':'150%' } },
    { name : 'Интервал 200%'                    , element : 'div', styles : { 'line-height':'200%' } },
    { name : 'Интервал 250%'                    , element : 'div', styles : { 'line-height':'250%' } }
]);

