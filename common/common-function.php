<?php
/**
 * This function is used to trim array elements.
 * @parameter array
 */
function bridge_trim_deep($value)
{
	if ( is_array($value) )
	{
		$value = array_map('bridge_trim_deep', $value);
	}
	elseif ( is_object($value) )
	{
		$vars = get_object_vars( $value );
		foreach ( $vars as $key=>$data )
		{
			$value->{$key} = bridge_trim_deep( $data );
		}
	}
	else
	{
		$value = trim(removeScript( $value ) );
	}

	return $value;
}

/**
 * This function is used to remove script tag.
 * @parameter string
 */
function removeScript($data){
	return preg_replace("/<script.+?>.+?<\/script>/im","",$data);
}

function selectMenuItem( $request_page )
{
?>
	<script>
		var selectedPage 		= '<?php echo $request_page;?>';

		if( selectedPage == 'index'){
			$( document ).find('a').each(function() {
				if($(this).text() == 'Home'){
					 $(this).css('color','#8a6d3b');
				}
			});
		}
		else if( selectedPage == 'categories'){
			$( document ).find('a').each(function() {
				if($(this).text() == 'Categories'){
					 $(this).css('color','#8a6d3b');
				}
			});
		}
		else if( selectedPage == 'products'){
			$( document ).find('a').each(function() {
				if($(this).text() == 'Products'){
					 $(this).css('color','#8a6d3b');
				}
			});
		}
		else if( selectedPage == 'purchases'){
			$( document ).find('a').each(function() {
				if($(this).text() == 'Purchases'){
					 $(this).css('color','#8a6d3b');
				}
			});
		}
		else if( selectedPage == 'customers'){
			$( document ).find('a').each(function() {
				if($(this).text() == 'Customers'){
					 $(this).css('color','#8a6d3b');
				}
			});
		}
	</script>
	<?php
}