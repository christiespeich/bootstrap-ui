<?php
namespace BootstrapUI\View\Helper;

class HtmlHelper extends \Cake\View\Helper\HtmlHelper
{
    use OptionsAwareTrait;

    /**
     * Returns Bootstrap badge markup. By default, uses `<SPAN>`.
     *
     * @param string $text Text to show in badge.
     * @param array $options Additional HTML attributes.
     * @return string HTML badge markup.
     */
    public function badge($text, array $options = [])
    {
        $options += ['tag' => 'span'];
        $tag = $options['tag'];
        unset($options['tag']);

        return $this->tag($tag, $text, $this->injectClasses('badge', $options));
    }

    /**
     * Returns breadcrumbs as a (x)html list
     *
     * This method uses HtmlHelper::tag() to generate list and its elements. Works
     * similar to HtmlHelper::getCrumbs(), so it uses options which every
     * crumb was added with.
     *
     * ### Options
     *
     * - `separator` Separator content to insert in between breadcrumbs, defaults to ''
     * - `firstClass` Class for wrapper tag on the first breadcrumb, defaults to 'first'
     * - `lastClass` Class for wrapper tag on current active page, defaults to 'last'
     *
     * @param array $options Array of HTML attributes to apply to the generated list elements.
     * @param string|array|bool $startText This will be the first crumb, if false it defaults to first crumb in
     *   array. Can also be an array, see `HtmlHelper::getCrumbs` for details.
     * @return string|null Breadcrumbs HTML list.
     * @link http://book.cakephp.org/3.0/en/views/helpers/html.html#creating-breadcrumb-trails-with-htmlhelper
     */
    public function getCrumbList(array $options = [], $startText = false)
    {
        $options += [
            'separator' => '',
        ];

        return parent::getCrumbList($this->injectClasses('breadcrumb', $options), $startText);
    }

    /**
     * Returns Bootstrap icon markup. By default, uses `<I>` and `glypicon`.
     *
     * @param string $name Name of icon (i.e. search, leaf, etc.).
     * @param array $options Additional HTML attributes.
     * @return string HTML icon markup.
     */
    public function icon($name, array $options = [])
    {
        $options += [
            'tag' => 'i',
            'iconSet' => 'glyphicon',
            'class' => null,
        ];

        $classes = [$options['iconSet'], $options['iconSet'] . '-' . $name];
        $options = $this->injectClasses($classes, $options);

        return $this->formatTemplate('tag', [
            'tag' => $options['tag'],
            'attrs' => $this->templater()->formatAttributes($options, ['tag', 'iconSet']),
        ]);
    }

    /**
     * Returns Bootstrap label markup. By default, uses `<SPAN>`.
     *
     * @param string $text Text to show in label.
     * @param array $options Additional HTML attributes.
     * @return string HTML icon markup.
     */
    public function label($text, $options = [])
    {
        if (is_string($options)) {
            $options = ['type' => $options];
        }

        $options += [
            'tag' => 'span',
            'type' => 'default',
        ];

        $classes = ['label', 'label-' . $options['type']];
        $tag = $options['tag'];
        unset($options['tag'], $options['type']);

        return $this->tag($tag, $text, $this->injectClasses($classes, $options));
    }
	
	/**
	 * Returns Bootstrap Carousel Markup
	 * 
	 * @param string $id ID of the carousel in outer DIV
	 * @param array $options options for the carousel
	 * @param array $items the photos and optional captions
	 * @return string HTML badge markup.
	 */
	public function carousel( $id = 'MyCarousel', $outer_tag = 'div', array $options = [], array $items = [] ) {
		// if no items, bail
		if ( !is_array( $items) || count( $items  ) == 0 ) {
			return '';
		}
			
		// set defaults
		$default_item_options = [
			'src'		=>	'',
			'alt'		=>	'',
			'caption'	=>	'',
			'link'		=>	'',
		];
		
		$default_options = [
			'data-ride'		=>	'carousel',
			'data-interval'	=>	'5000',
			'data-pause'	=>	'hover',
			'data-wrap'		=>	'true',
			'data-keyboard'	=>	'true',
			'class'			=>	'carousel',
			'use-glyph'		=>	true,
			'slide'			=>	true,
		];
		$options += $default_options;
				
		$use_glyph =  $options['use-glyph'];
		$class = 'carousel';
		if ( $options['slide'] ) {
			$class .= ' slide';
		}
		unset( $options['use-glyph'] );
		unset( $options['slide'] );
		$options['id'] = h($id);
				
		
		$indicator_items = '';
		$item = '';
		for ( $x = 0; $x < count( $items ); $x++ ) {
			
			// INDICATORS
			$indicator_options = [  'data-target' => '#' . $options['id'],
									'data-slide-to'  =>  $x,
								];
			if ( $x == 0 ) {
				$indicator_options[ 'class' ] = 'active';
			}
			
			$indicator_items .= $this->tag( 'li', '', $indicator_options );
			
			// ITEMS
			// set defaults for items			
			$items[ $x ] +=  $default_item_options;
			
			// caption
			$caption = '';
			if ( $items[ $x ][ 'caption' ] != '' ) {
				$caption = $this->tag( 'div', $items[ $x ][ 'caption' ], [ 'class' => 'carousel-caption' ] );
			}
			
			// image
			$image_options = [];
			if ( $items[ $x ][ 'link' ] != '' ) {
				$image_options[ 'url' ] = $items[ $x ][ 'link' ];
			}
			$image = $this->image( $items[ $x ][ 'src' ], $image_options );
			$class = 'item';
			if ( $x == 0 ) {
				$class .= ' active';
			}
			$item .= $this->tag( 'div', $image . $caption, [ 'class'	=>	$class ] );
		}
		$indicators = $this->tag( 'div', $indicator_items, [ 'class' => 'carousel-indicators' ] );
		
		$wrapper = $this->tag( 'div', $item, [ 'class'	=>	'carousel-inner', 'role'	=>	'listbox' ] );
		
		// controls
		if ( $use_glyph ) {
			$class_prev = 'glypicon glyphicon-chevron-left';
			$class_next = 'glypicon glyphicon-chevron-right';
		} else {
			$class_prev = 'icon-prev';
			$class_next = 'icon-next';
		}
		$previous = $this->tag( 'span', '', [ 'class'	=> $class_prev	, 'aria-hidden'	=>	'true' ] );
		$previous .= $this->tag( 'span', 'Previous', [ 'class'	=>	'sr-only' ] );
		
		$next = $this->tag( 'span', '', [ 'class'	=>	$class_next, 'aria-hidden'	=>	'true' ] );
		$next .= $this->tag( 'span', 'Next', [ 'class'	=>	'sr-only' ] );
		
		$controls = $this->tag( 'a', $previous,  [ 'href'	=>	'#' . $options['id'], 'role'	=>	'button', 'data-slide' => 'prev', 'class'	=>	'left carousel-control' ] );
		$controls .= $this->tag( 'a', $next,  [ 'href'	=>	'#' . $options['id'], 'role'	=>	'button', 'data-slide' => 'next', 'class'	=>	'right carousel-control' ] );
		
		// outer wrapper
		return $this->tag( $outer_tag, $indicators . $wrapper . $controls, $options  );
		
	}
}
