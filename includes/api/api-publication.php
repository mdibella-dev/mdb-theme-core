<?php
/**
 * Functions for post-type publications.
 *
 * @author  Marco Di Bella
 * @package mdb-theme-core
 * @uses    ACF
 */


namespace mdb_theme_core\api;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * Checks if the id belongs to a publication.
 *
 * @since 1.1.0
 *
 * @param int $id The ID of the publication.
 *
 * @return bool The outcome of the check (true/false).
 */

function is_publication( $id ) {
    return (bool) ( 'publication' == get_post_type( $id ) );
}



/**
 * Returns a record of publication data.
 *
 * @since 1.1.0
 *
 * @param int $id The ID of the publication.
 *
 * @return array The record.
 */

function get_data( $id ) {
    $data         = [];
    $data['type'] = get_field( 'ref_type', $id );


    // General information dependent on the document type.
    $spec = get_field( 'dokumenttypspezifische_angaben', $id );

    foreach( (array) $spec as $key => $value ) {
        $newkey = substr( $key, 4 );        // without ref_

        $data[$newkey] = $spec[$key];
    }


    // Citations of the document.
    $citations = get_field( 'citations', $id );
    $citation  = [];

    if ( ! empty( $citations ) ) {
        foreach( (array) $citations as $key => $value ) {
            $citation[] = $value['cite'];
        }

        $data['citation'] = $citation;
    }


    // Literature used.
    $references = get_field( 'references', $id );
    $reference  = [];

    if ( ! empty( $references ) ) {
        foreach( $references as $key => $value ) {
            $reference[] = $value['reference'];
        }

        $data['reference'] = $reference;
    }


    return $data;
}



/**
 * Generates a RIS file.
 *
 * @since  1.1.0
 *
 * @source https://github.com/aurimasv/translators/wiki/RIS-Tag-Map
 * @source https://de.wikipedia.org/wiki/RIS_(Dateiformat)
 * @source https://www1.citavi.com/sub/manual6/de/index.html?importing_a_ris_file.html
 *
 * @param int $id The ID of the publication.
 */

function build_ris_file( $id ) {
    $data  = get_data( $id );
    $title = normalize_title( $data['title'], $data['subtitle'] );
    $type  = [
        0 => 'CHAP',
        2 => 'BOOK',
        3 => 'BOOK',
        5 => 'UNPB',
        6 => 'ICOMM',
        7 => 'JOUR',
        8 => 'NEWS',
        9 => 'CONF',
    ];
    $ris   = [];


    // TY - document type
    // Must be the first tag!
    $ris[] = [ 'TY', $type[$data['type']] ];


    // TI - title
    // (alternative: T1)
    if ( ! empty( $data['title'] ) or ! empty( $data['subtitle'] ) ) {
        $ris[] = [ 'TI', $title ];
    }


    // AU - authors
    // (alternative: A1)
    if ( ! empty( $data['authors'] ) ) {
        foreach ( $data['authors'] as $author ) {
            if ( ! empty( $author['au_lastname'] ) ) {
                if ( ! empty( $author['au_firstname'] ) ) {
                    $ris[] = [ 'AU', sprintf( '%2$s, %1$s', $author['au_firstname'], $author['au_lastname'] ) ];
                } else {
                    $ris[] = [ 'AU', $author['au_lastname'] ];
                }
            }
        }
    }


    // ED - editors
    // (alternative: A2)
    if ( ! empty( $data['editors'] ) ) {
        foreach ( $data['editors'] as $editor ) {
            if ( ! empty( $editor['ed_lastname'] ) ) {
                if ( ! empty( $editor['ed_firstname'] ) ) {
                    $ris[] =[ 'ED', sprintf( '%2$s, %1$s', $editor['ed_firstname'], $editor['ed_lastname'] ) ];
                } else {
                    $ris[] = [ 'ED', $editor['au_lastname'] ];
                }
            }
        }
    }


    // Document type specific information.
    switch ( $type[ $data['type'] ] ) {

        case 'CHAP' :
            if ( ! empty( $data['booktitle'] ) ) {
                $ris[] = [ 'BT', normalize_title( $data['booktitle'], $data['booksubtitle'] ) ];
            }
            break;

        case 'BOOK' :
        case 'UNPB' :
            if ( ! empty( $data['title'] ) ) {
                $ris[] =[ 'BT', $title ];
            }
            break;
    }


    // Tags that can be easily adopted.
    $tags = [
        'journalfullname'       => 'JF',
        'journalabbreviation'   => 'JO',
        'startpage'             => 'SP',
        'endpage'               => 'EP',
        'volume'                => 'VL',
        'issue'                 => 'IS',
        'edition'               => 'ET',
        'publisher'             => 'PB',
        'pubyear'               => 'PY',
        'pubplace'              => 'PP',
        'pubplace'              => 'CY',
        'serialnumber'          => 'SN',
    ];

    foreach ( $tags as $key => $tag ) {
        if ( ! empty( $data[$key] ) ) {
            $ris[] = [ $tag, $data[$key] ];
        }
    }


    // AB - abstract
    if ( '' !== get_post( $id )->post_content ) {
        $ris[] = [ 'AB', wp_strip_all_tags( get_post( $id )->post_content, true ) ];
    }


    // KW - keywords
    if ( ! empty( $data['keywords'] ) ) {
        foreach ( $data['keywords'] as $keyword ) {
            $ris[] = [ 'KW', $keyword->name ];
        }
    }


    // ER - end of RIS
    // Must be the last tag
    $ris[] = [ 'ER', '' ];


    // Write data to the file along the $ris matrix
    $filename = sprintf( 'mdb%1$s.ris', $id );

    header( "Content-type: plain/text", true, 200 );
    header( "Content-Disposition: attachment; filename=" . $filename );
    header( "Pragma: no-cache" );
    header( "Expires: 0" );

    foreach ( $ris as $line ) {
        echo sprintf( '%1$s  - %2$s', $line[0], $line[1] );
        echo "\r\n";
    }
}



/**
 * Uses the template redirect hook to trigger the creation and download of citation files.
 *
 * @since 1.1.0
 *
 * @see https://wordpress.stackexchange.com/questions/3480/how-can-i-force-a-file-download-in-the-wordpress-backend
 */

function download_ris_file() {
    if ( false !== strstr( $_SERVER['REQUEST_URI'], '/citation/' ) ) {

        $id = (int) substr( strrchr( $_SERVER['REQUEST_URI'], "/" ), 1);

        build_ris_file( $id ); // Currently only RIS is supported!
        exit();
    }
}

add_action( 'template_redirect', __NAMESPACE__ . '\download_ris_file' );



/**
 * Creates a comma-separated list of authors (or editors) according to international scholarly notation.
 *
 * Samples:
 * - Hans-Joachim Schmitz   => Schmitz HJ
 * - Hans Peter Albers      => Peters HP
 * - Marco Di Bella         => Di Bella M
 * - Frank U Montgomery     => Montgomery FU
 *
 * @since 1.1.0
 *
 * @param array $persons List of authors.
 *
 * @return string The comma separated list of authors.
 * @return bool   In case of an error: false.
 */

function normalize_names( $persons ) {
    if ( ! empty( $persons ) ) {

        // Removes prefixs; au_ and ed_ fields should not coexist.
        $replacements = [
            'au_lastname'   => 'lastname',
            'au_firstname'  => 'firstname',
            'ed_lastname'   => 'lastname',
            'ed_firstname'  => 'firstname',
        ];

        foreach ( $persons as &$person ) {
            foreach ( $replacements as $oldkey => $newkey ) {
                if ( true === array_key_exists( $oldkey, $person ) ) {
                    $person[$newkey] = $person[$oldkey];
                    unset( $person[$oldkey] );
                }
            }
        }

        unset( $person );


        // Shorten first names and compile a list of names.
        $names = [];

        foreach ( $persons as $person ) {
            if ( ! empty( $person['lastname'] ) ) {

                $shorten_and_concate = '';

                if ( ! empty( $person['firstname'] ) ) {
                    $firstnames = preg_split( "/[\s-]+/", $person['firstname'] );

                    foreach( $firstnames as $firstname ) {
                        $shorten_and_concate .= strtoupper( $firstname[0] );
                    }
                }

                if( ! empty( $shorten_and_concate ) ) {
                    $names[] = $person['lastname'] . ' ' . $shorten_and_concate;
                } else {
                    $names[] = $person['lastname'];
                }

            }
        }


        // Return a list of names if available.
        if ( ! empty( $names ) ) {
            return implode( ', ', $names );
        }
    }

    return false;
}



/**
 * Combines the title and subtitle of a publication. Adds punctuation if necessary.
 *
 * @since 1.1.0
 *
 * @param string $title    The title.
 * @param string $subtitle The subtitle.
 *
 * @return string The combined title.
 */

function normalize_title( $title, $subtitle ) {
    // Add title components (if not empty).
    if ( ! empty( $title ) ) {
        $components[] = $title;
    }

    if ( ! empty( $subtitle ) ) {
        $components[] = $subtitle;
    }


    // Add punctuation.
    $punctuation = [ '?', '!', '.', ':' ];
    $count       = 0;

    foreach ( $components as &$component ) {
        $count++;
        $lastchar = substr( $component, -1, 1 );

        if ( true !== in_array( $lastchar, $punctuation ) ) {
            if( $count !== sizeof( $components ) ) {
                // There must be a colon between the components.
                $component .= ':';
            } else {
                // Last component gets a point.
                $component .= '.';
            }
        }
    }


    // Join components by using spaces.
    return implode( ' ', $components );
}



/**
 * Creates a citation suggestion for the specified publication.
 *
 * @since 1.1.0
 *
 * @param int $id The ID of the publication.
 *
 * @return string The suggested citation.
 */

function build_citation( $id, $build_mode = MDB_BUILD_STRING ) {
    $data   = get_data( $id );
    $output = [
        0 => '',
        1 => ''
    ];
    $type   = [
        0 => 'CHAPTER',
        2 => 'BOOK_MONO',
        3 => 'BOOK_EDITION',
        5 => 'GRAY',
        6 => 'WEB',
        7 => 'ARTICLE',
        8 => 'ARTICLE',
        9 => 'CONF',
    ];


    // Process authors.
    if ( ! empty( $data['authors'] ) ) {
        $output[0] .= normalize_names( $data['authors'] );
    } else {
        $output[0] .= __( 'without author', 'mdb-theme-core' );
    }


    // Process year of publication.
    if ( ! empty( $data['pubyear'] ) ) {
        $output[0] .= ' (' . $data['pubyear'] . ')';
    }


    // Add 'separation'.
    $output[0] .= ': ';


    // Process title and subtitle.
    if ( ! empty( $data['title'] ) or ! empty( $data['subtitle'] ) ) {
        $title = normalize_title( $data['title'], $data['subtitle'] );

        // Is it a book chapter, article in magazine or newspaper?
        if ( ( 'CHAPTER' === $type[$data['type']] ) or ( 'ARTICLE' === $type[$data['type']] ) ) {
            $title = '"' . $title . '"';
        }
    } else {
        $title = __( 'without a title', 'mdb-theme-core' );
    }

    $output[0] .= $title;


    // Process additions to the title.
    if ( ! empty( $data['titleaddition'] ) ) {

        $last = substr( $data['titleaddition'], -1 );

        $output[0] .= ' ' . $data['titleaddition'];

        if ( true !== in_array( $last, [ '?', '!', '.' ] ) ) {
            $output[0] .= '.';
        }
    }


    // Is it an article in a magazine or in a newspaper?
    if ( 'ARTICLE' === $type[$data['type']] ) {

        $output[1] .= __(' In: ', 'mdb-theme-core' );

        if( ! empty( $data['journalabbreviation'] ) ) {
            $output[1] .= $data['journalabbreviation'] . ' ';
        } else {
            $output[1] .= $data['journalfullname'] . ' ';
        }

        if( ! empty( $data['volume'] ) ) {
            $output[1] .= $data['volume'];
        }

        if( ! empty( $data['issue'] ) ) {
            $output[1] .= '(' . $data['issue'] .')';
        }

    } else {

        // Is it a book chapter?
        if ( 'CHAPTER' === $type[$data['type']] ) {
            $editors = '';

            // Identify publishers.
            if ( ! empty( $data['editors'] ) ) {
                $editors .= normalize_names( $data['editors'] );
            } else {
                $editors .= __( 'without editor', 'mdb-theme-core' );
            }

            $output[1] .= sprintf(
                __( ' In: %1$s [ed.]: %2$s', 'mdb-theme-core' ),
                $editors,
                normalize_title( $data['booktitle'], $data['booksubtitle'] )
            );
        }


        // Is it part of a series?
        if ( ! empty( $data['edition'] ) ) {

            if ( ! empty( $data['issue'] ) ) {
                $output[1] .= sprintf(
                    __( ' Volume %1$s of the "%2$s" book series.', 'mdb-theme-core' ),
                    $data['issue'],
                    $data['edition'],
                );
            } else {
                $output[1] .= sprintf(
                    __( ' From the "%1$s" book series.', 'mdb-theme-core' ),
                    $data['edition'],
                );
            }
        }


        // Process place of publication.
        if ( ! empty( $data['pubplace'] ) ) {
            $output[1] .= ' ' . $data['pubplace'];
        }
    }


    // Process the start and end page.
    if ( ! empty( $data['startpage'] ) ) {
        if ( ! empty( $data['endpage'] ) ) {
            $output[1] .= sprintf(
                ', S. %1$s-%2$s',
                $data['startpage'],
                $data['endpage'],
            );
        } else {
            $output[1] .= sprintf(
                ', S. %1$s',
                $data['startpage'],
            );
        }
    }


    // Improve typography (when plugin wp-typography is loaded).
    if ( class_exists( 'WP_Typography' ) ) {
        $output[0] = \WP_Typography::process_title( $output[0] );
        $output[1] = \WP_Typography::process_title( $output[1] );
    }


    // Do the output.
    if ( MDB_BUILD_ARRAY == $build_mode ) {
        return $output;
    }

    return implode( '', $output );
}
