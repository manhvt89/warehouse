<?php
echo $this->lang->line('error_no_permission_module').' '.$module_name . (!empty($permission_id) ? ' (' . $permission_id . ')' : '');
echo '<br/> <a href="/"> Trang chủ</a>';
?> 