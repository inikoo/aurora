<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2016 at 16:18:43 BST, Sheffield, UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

include_once 'utils/date_functions.php';


function get_part_family_showcase($data, $smarty) {



	$category=$data['_object'];
	if (!$category->id) {
		return "";
	}

	$category->load_acc_data();

	$smarty->assign('category', $category);

	$images=$category->get_images_slidesshow();

	if (count($images)>0) {
		$main_image=$images[0];
	}else {
		$main_image='';
	}


	$smarty->assign('main_image', $main_image);
	$smarty->assign('images', $images);


	$smarty->assign('quarter_data',
		array(
			array('header'=>get_quarter_label(strtotime('now')),
				'invoiced_amount_delta_title'=>delta($category->get('Part Category Quarter To Day Acc Invoiced Amount'), $category->get('Part Category Quarter To Day Acc 1YB Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category Quarter To Day Acc Invoiced Amount')>$category->get('Part Category Quarter To Day Acc 1YB Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category Quarter To Day Acc Invoiced Amount')<$category->get('Part Category Quarter To Day Acc 1YB Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category Quarter To Day Acc Dispatched'), $category->get('Part Category Quarter To Day Acc 1YB Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category Quarter To Day Acc Dispatched')>$category->get('Part Category Quarter To Day Acc 1YB Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category Quarter To Day Acc Dispatched')<$category->get('Part Category Quarter To Day Acc 1YB Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)

			),
			array('header'=>get_quarter_label(strtotime('now -3 months')),
				'invoiced_amount_delta_title'=>delta($category->get('Part Category 1 Quarter Ago Invoiced Amount'), $category->get('Part Category 1 Quarter Ago 1YB Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category 1 Quarter Ago Invoiced Amount')>$category->get('Part Category 1 Quarter Ago 1YB Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 1 Quarter Ago Invoiced Amount')<$category->get('Part Category 1 Quarter Ago 1YB Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category 1 Quarter Ago Dispatched'), $category->get('Part Category 1 Quarter Ago 1YB Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category 1 Quarter Ago Dispatched')>$category->get('Part Category 1 Quarter Ago 1YB Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 1 Quarter Ago Dispatched')<$category->get('Part Category 1 Quarter Ago 1YB Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)

			),
			array('header'=>get_quarter_label(strtotime('now -6 months')),
				'invoiced_amount_delta_title'=>delta($category->get('Part Category 2 Quarter Ago Invoiced Amount'), $category->get('Part Category 2 Quarter Ago 1YB Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category 2 Quarter Ago Invoiced Amount')>$category->get('Part Category 2 Quarter Ago 1YB Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 2 Quarter Ago Invoiced Amount')<$category->get('Part Category 2 Quarter Ago 1YB Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category 2 Quarter Ago Dispatched'), $category->get('Part Category 2 Quarter Ago 1YB Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category 2 Quarter Ago Dispatched')>$category->get('Part Category 2 Quarter Ago 1YB Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 2 Quarter Ago Dispatched')<$category->get('Part Category 2 Quarter Ago 1YB Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)

			),
			array('header'=>get_quarter_label(strtotime('now -9 months')),
				'invoiced_amount_delta_title'=>delta($category->get('Part Category 3 Quarter Ago Invoiced Amount'), $category->get('Part Category 3 Quarter Ago 1YB Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category 3 Quarter Ago Invoiced Amount')>$category->get('Part Category 3 Quarter Ago 1YB Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 3 Quarter Ago Invoiced Amount')<$category->get('Part Category 3 Quarter Ago 1YB Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category 3 Quarter Ago Dispatched'), $category->get('Part Category 3 Quarter Ago 1YB Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category 3 Quarter Ago Dispatched')>$category->get('Part Category 3 Quarter Ago 1YB Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 3 Quarter Ago Dispatched')<$category->get('Part Category 3 Quarter Ago 1YB Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)
			),
			array('header'=>get_quarter_label(strtotime('now -12 months')),
				'invoiced_amount_delta_title'=>delta($category->get('Part Category 4 Quarter Ago Invoiced Amount'), $category->get('Part Category 4 Quarter Ago 1YB Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category 4 Quarter Ago Invoiced Amount')>$category->get('Part Category 4 Quarter Ago 1YB Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 4 Quarter Ago Invoiced Amount')<$category->get('Part Category 4 Quarter Ago 1YB Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category 4 Quarter Ago Dispatched'), $category->get('Part Category 4 Quarter Ago 1YB Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category 4 Quarter Ago Dispatched')>$category->get('Part Category 4 Quarter Ago 1YB Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 4 Quarter Ago Dispatched')<$category->get('Part Category 4 Quarter Ago 1YB Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)
			),
		));
	$smarty->assign('year_data',
		array(
			array('header'=>date('Y', strtotime('now')) ,
				'invoiced_amount_delta_title'=>delta($category->get('Part Category Year To Day Acc Invoiced Amount'), $category->get('Part Category Year To Day Acc 1YB Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category Year To Day Acc Invoiced Amount')>$category->get('Part Category Year To Day Acc 1YB Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category Year To Day Acc Invoiced Amount')<$category->get('Part Category Year To Day Acc 1YB Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category Year To Day Acc Dispatched'), $category->get('Part Category Year To Day Acc 1YB Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category Year To Day Acc Dispatched')>$category->get('Part Category Year To Day Acc 1YB Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category Year To Day Acc Dispatched')<$category->get('Part Category Year To Day Acc 1YB Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)

			),
			array('header'=>date('Y', strtotime('now -1 year')),
				'invoiced_amount_delta_title'=>delta($category->get('Part Category 1 Year Ago Invoiced Amount'), $category->get('Part Category 2 Year Ago Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category 1 Year Ago Invoiced Amount')>$category->get('Part Category 2 Year Ago Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 1 Year Ago Invoiced Amount')<$category->get('Part Category 2 Year Ago Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category 1 Year Ago Dispatched'), $category->get('Part Category 2 Year Ago Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category 1 Year Ago Dispatched')>$category->get('Part Category 2 Year Ago Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 1 Year Ago Dispatched')<$category->get('Part Category 2 Year Ago Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)
			),
			array('header'=>date('Y', strtotime('now -2 year')),
				'invoiced_amount_delta_title'=>delta($category->get('Part Category 2 Year Ago Invoiced Amount'), $category->get('Part Category 3 Year Ago Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category 2 Year Ago Invoiced Amount')>$category->get('Part Category 3 Year Ago Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 2 Year Ago Invoiced Amount')<$category->get('Part Category 3 Year Ago Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category 2 Year Ago Dispatched'), $category->get('Part Category 3 Year Ago Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category 2 Year Ago Dispatched')>$category->get('Part Category 3 Year Ago Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 2 Year Ago Dispatched')<$category->get('Part Category 3 Year Ago Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)
			),
			array('header'=>date('Y', strtotime('now -3 year')),
				'invoiced_amount_delta_title'=>delta($category->get('Part Category 3 Year Ago Invoiced Amount'), $category->get('Part Category 4 Year Ago Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category 3 Year Ago Invoiced Amoun')>$category->get('Part Category 4 Year Ago Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 3 Year Ago Invoiced Amount')<$category->get('Part Category 4 Year Ago Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category 3 Year Ago Dispatched'), $category->get('Part Category 4 Year Ago Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category 3 Year Ago Invoiced Amoun')>$category->get('Part Category 4 Year Ago Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 3 Year Ago Dispatched')<$category->get('Part Category 4 Year Ago Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)

			),
			array('header'=>date('Y', strtotime('now -4 year')),
				'invoiced_amount_delta_title'=>delta($category->get('Part Category 4 Year Ago Invoiced Amount'), $category->get('Part Category 5 Year Ago Invoiced Amount')),
				'invoiced_amount_delta'=>
				(
					$category->get('Part Category 4 Year Ago Invoiced Amount')>$category->get('Part Category 5 Year Ago Invoiced Amount')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 4 Year Ago Invoiced Amount')<$category->get('Part Category 5 Year Ago Invoiced Amount')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				),
				'dispatched_delta_title'=>delta($category->get('Part Category 4 Year Ago Dispatched'), $category->get('Part Category 5 Year Ago Dispatched')),
				'dispatched_delta'=>
				(
					$category->get('Part Category 4 Year Ago Dispatched')>$category->get('Part Category 5 Year Ago Dispatched')
					?'<i class="fa fa-fw fa-play fa-rotate-270 success" aria-hidden="true"></i>':
					($category->get('Part Category 4 Year Ago Dispatched')<$category->get('Part Category 5 Year Ago Dispatched')?
						'<i class="fa fa-fw fa-play fa-rotate-90 error" aria-hidden="true"></i>':''
					)
				)
			)
		));

	$customers=sprintf('<i class="fa fa-users padding_right_5" aria-hidden="true"></i> %s', $category->get('Total Acc Customers'));

	$smarty->assign('customers', $customers);

	$smarty->assign('header_total_sales', sprintf(_('All sales since: %s'), $category->get('Valid From')));


	return $smarty->fetch('showcase/part_family.tpl');



}


?>
