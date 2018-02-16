<?

	$TMPL->addVar("TMPL_users", $result->GetRows());
	$TMPL->ParseIntoVar($_CENTER,"telephone");
