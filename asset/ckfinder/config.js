/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://cksource.com/ckfinder/license
*/

CKFinder.customConfig = function( config )
{
	// Define changes to default configuration here.
	// For the list of available options, check:
	// http://docs.cksource.com/ckfinder_2.x_api/symbols/CKFinder.config.html

	// Sample configuration options:
	// config.uiColor = '#BDE31E';
     config.gallery_autoLoad = true; // используем для просмотра изображений colorbox
     config.disableThumbnailSelection = true; // автоматическое создание миниатюр
     config.defaultLanguage = 'ru';
	 config.language = 'ru'; // язык
	 config.removePlugins = 'basket'; // убираем плагин корзины
     config.showContextMenuArrow = true; // показывать стрелку выбора (как контекстное меню при нажатии правой кнопкой)
     config.startupPath = 'Images:/';  // начальная папка      
};
