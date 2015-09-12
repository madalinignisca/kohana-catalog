<?php defined('SYSPATH') or die('No direct access allowed.');

	if ( ! empty($breadcrumbs)):
?>
		<div class="row">
			<div class="span9">
				<ul class="breadcrumb kr-breadcrumb">
<?php
				$count_breadcrumbs = count($breadcrumbs);
				foreach ($breadcrumbs as $_item) {
					$count_breadcrumbs--;
					if ($count_breadcrumbs > 0) {
						echo '<li>', 
							(empty($_item['icon']) ? '' : '<i class="icon-folder-open"></i>&nbsp;'),
							HTML::anchor($_item['link'], $_item['title']),
							'<span class="divider">/</span></li>';
					} else {
						echo '<li>', 
							(empty($_item['icon']) ? '' : '<i class="icon-folder-open"></i>&nbsp;'),
							HTML::chars($_item['title']),
							'</li>';
					}
					
				}
?>				
				</ul>
			</div>
		</div>
<?php 
	endif;
	if ( $list->count() > 0 ): 
		$query_array = array(
			'category' => '--CATEGORY_ID--',
		);
		$open_tpl = Route::url('modules', array(
			'controller' => 'catalog',
			'query'      => Helper_Page::make_query_string($query_array),
		));
		$elements_list_tpl = Route::url('modules', array(
			'controller' => 'catalog',
			'action'     => 'category',
			'id'         => '{id}',
			'query'      => Helper_Page::make_query_string($query_array),
		));
		$query_array['category'] = $CATALOG_CATEGORY_ID;
		$delete_tpl = Route::url('modules', array(
			'controller' => 'catalog',
			'action'     => 'category_delete',
			'id'         => '{id}',
			'query'      => Helper_Page::make_query_string($query_array),
		));

		$p = Request::current()->query( Paginator::QUERY_PARAM );
		if ( ! empty($p)) {
			$query_array[ Paginator::QUERY_PARAM ] = $p;
		}
		$edit_tpl = Route::url('modules', array(
			'controller' => 'catalog',
			'action'     => 'category_edit',
			'id'         => '{id}',
			'query'      => Helper_Page::make_query_string($query_array),
		));
		
		
		// Position link templates
		$query_array['mode'] = 'up';
		$up_tpl	= Route::url('modules', array(
			'controller' => 'catalog',
			'action'     => 'category_position',
			'id'         => '{id}',
			'query'      => Helper_Page::make_query_string($query_array),
		));
		
		$query_array['mode'] = 'down';
		$down_tpl = Route::url('modules', array(
			'controller' => 'catalog',
			'action'     => 'category_position',
			'id'         => '{id}',
			'query'      => Helper_Page::make_query_string($query_array),
		));
		
		$query_array['mode'] = 'first';
		$first_tpl =  Route::url('modules', array(
			'controller' => 'catalog',
			'action' => 'category_position',
			'id' => '{id}',
			'query'	=> Helper_Page::make_query_string($query_array),
		));
		
		$query_array['mode'] = 'last';
		$last_tpl =  Route::url('modules', array(
			'controller' => 'catalog',
			'action' => 'category_position',
			'id' => '{id}',
			'query'	=> Helper_Page::make_query_string($query_array),
		));
		
?>
		<table class="table table-bordered table-striped">
			<colgroup>
				<col class="span1">
				<col class="span2">
				<col class="span4">
				<col class="span2">
			</colgroup>
			<thead>
				<tr>
					<th><?php echo __('ID'); ?></th>
					<th><?php echo __('Image'); ?></th>
					<th><?php echo __('Title'); ?></th>
					<th><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
<?php 
			$wrapper = ORM_Helper::factory('catalog_Category');
			foreach ($list as $_orm):
?>
			<tr>
				<td><?php echo $_orm->id ?></td>
				<td>
<?php
				if ($_orm->image) {
					$img_size = getimagesize(DOCROOT.$wrapper->file_path('image', $_orm->image));
					
					if ($img_size[0] > 100 OR $img_size[1] > 100) {
						$thumb = Thumb::uri('admin_image_100', $wrapper->file_uri('image', $_orm->image));
					} else {
						$thumb = $wrapper->file_uri('image', $_orm->image);
					}
					
					if ($img_size[0] > 300 OR $img_size[1] > 300) {
						$flyout = Thumb::uri('admin_image_300', $wrapper->file_uri('image', $_orm->image));
					} else {
						$flyout = $wrapper->file_uri('image', $_orm->image);
					}
					
					echo HTML::anchor($flyout, HTML::image($thumb, array(
						'title' => ''
					)), array(
						'class' => 'js-photo-gallery',
					));
				} else {
					echo __('No image');
				}
?>				
				</td>
				<td>
<?php
					if ( (bool) $_orm->active) {
						echo '<i class="icon-eye-open"></i>&nbsp;';
					} else {
						echo '<i class="icon-eye-open" style="background: none;"></i>&nbsp;';
					}
					echo HTML::chars($_orm->title)
?>
				</td>
				<td>
<?php
				if ($ACL->is_allowed($USER, $_orm, 'edit')) {
					
					echo '<div class="btn-group">';
					
						echo HTML::anchor(str_replace('--CATEGORY_ID--', $_orm->id, $open_tpl), '<i class="icon-folder-open"></i> '.__('Open'), array(
							'class' => 'btn',
							'title' => __('Open category'),
						));
					
						echo '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>';
						echo '<ul class="dropdown-menu">';
						
							echo '<li>', HTML::anchor(str_replace(array('{id}' , '--CATEGORY_ID--'), $_orm->id, $elements_list_tpl), '<i class="icon-list"></i> '.__('Elements list'), array(
								'title' => __('Elements list'),
							)), '</li>';
						
							echo '<li class="divider"></li>';
							
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $first_tpl), '<i class="icon-arrow-first"></i> '.__('Move first'), array(
								'title' => __('Move first'),
							)), '</li>';
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $up_tpl), '<i class="icon-arrow-up"></i> '.__('Move up'), array(
								'title' => __('Move up'),
							)), '</li>';
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $down_tpl), '<i class="icon-arrow-down"></i> '.__('Move down'), array(
								'title' => __('Move down'),
							)), '</li>';
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $last_tpl), '<i class="icon-arrow-last"></i> '.__('Move last'), array(
								'title' => __('Move last'),
							)), '</li>';
						
							echo '<li class="divider"></li>';
							
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $edit_tpl), '<i class="icon-edit"></i> '.__('Edit'), array(
								'title' => __('Edit'),
							)), '</li>';
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $delete_tpl), '<i class="icon-remove"></i> '.__('Delete'), array(
								'class' => 'delete_button',
								'title' => __('Delete'),
							)), '</li>';
						echo '</ul>';
					echo '</div>';
					
				} else {
					
					echo HTML::anchor(str_replace('{id}', $_orm->id, $open_tpl), '<i class="icon-list"></i> '.__('Elements'), array(
						'class' => 'btn',
						'title' => __('Elements list'),
					));
					
				}
?>
				</td>
			</tr>
<?php 
		endforeach;
?>
		</tbody>
	</table>
<?php
	$link = Route::url('modules', array(
		'controller' => 'catalog',
		'query'      => Helper_Page::make_query_string(array(
			'category' => $CATALOG_CATEGORY_ID,
		)),
	));

	echo $paginator->render($link);
endif;
