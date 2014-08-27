/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/*
 * This file is used/requested by the 'Styles' button.
 * The 'Styles' button is not enabled by default in DrupalFull and DrupalFiltered toolbars.
 */
if(typeof(CKEDITOR) !== 'undefined') {
    CKEDITOR.addStylesSet( 'drupal',
    [

        /* Bootstrap Styles */

        /* Typography */
        { name : 'span.H1'        , element : 'span', attributes: { 'class': 'h1' } },
        { name : 'span.H2'        , element : 'span', attributes: { 'class': 'h2' } },
        { name : 'span.H3'        , element : 'span', attributes: { 'class': 'h3' } },
        { name : 'span.H4'        , element : 'span', attributes: { 'class': 'h4' } },
        { name : 'span.H5'        , element : 'span', attributes: { 'class': 'h5' } },
        { name : 'span.H6'        , element : 'span', attributes: { 'class': 'h6' } },

        { name : 'Paragraph Lead'     , element : 'p', attributes: { 'class': 'lead' } },

        {
            name : 'Unstyled List',
            element : 'ul',
            attributes :
            {
                'class' : 'list-unstyled'
            }
        },
        {
            name : 'List inline',
            element : 'ul',
            attributes :
            {
                'class' : 'list-inline'
            }
        },
        {
            name : 'Table',
            element : 'table',
            attributes :
            {
                'class' : 'table'
            }
        },
        {
            name : 'Table Striped rows',
            element : 'table',
            attributes :
            {
                'class' : 'table table-striped'
            }
        },
        {
            name : 'Table Bordered',
            element : 'table',
            attributes :
            {
                'class' : 'table table-bordered'
            }
        },
        {
            name : 'Table Hover rows',
            element : 'table',
            attributes :
            {
                'class' : 'table table-hover'
            }
        },
        {
            name : 'Table Condensed',
            element : 'table',
            attributes :
            {
                'class' : 'table table-condensed'
            }
        },
        {
            name : 'Image shap rounded',
            element : 'table',
            attributes :
            {
                'class' : 'img-rounded'
            }
        },
        {
            name : 'Image shap circle',
            element : 'table',
            attributes :
            {
                'class' : 'img-circle'
            }
        },
        {
            name : 'Image shap thumbnail',
            element : 'table',
            attributes :
            {
                'class' : 'img-thumbnail'
            }
        },
        {
            name : 'Image float left',
            element : 'table',
            attributes :
            {
                'class' : 'pull-left'
            }
        },
        {
            name : 'Image float right',
            element : 'table',
            attributes :
            {
                'class' : 'pull-right'
            }
        },

    ]);
}
