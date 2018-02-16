Calendar._DN  = new Array( "კვირა", "ორშაბათი", "სამშაბათი", "ოთხშაბათი", "ხუთშაბათი", "პარასკევი", "შაბათი", "კვირა" );
Calendar._SDN = new Array("კვ","ორ","სამ","ოთხ","ხუთ","პარ","შაბ","კვ");

// First day of the week. "0" means display Sunday first, "1" means display Monday first, etc.
Calendar._FD = 1;

// full month names
Calendar._MN  = new Array("იანვარი","თებერვალი","მარტი","აპრილი","მაისი","ივნისი","ივლისი","აგვისტო","სექტემბერი","ოქტომბერი","ნოემბერი","დეკემბერი");

// short month names
Calendar._SMN = new Array("იან","თებ","მარ","აპრ","მაი","ივნ","ივლ","აგვ","სექ","ოქტ","ნოე","დეკ");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"]  = "";//О календаре
Calendar._TT["ABOUT"] = "";
Calendar._TT["ABOUT_TIME"] = "";
Calendar._TT["PREV_YEAR"] = "წინა წელი";
Calendar._TT["PREV_MONTH"] = "წინა თვე";
Calendar._TT["GO_TODAY"] = "მიმდინარე თარიღი";
Calendar._TT["NEXT_MONTH"] = "შემდეგი თვე";
Calendar._TT["NEXT_YEAR"] = "შემდეგი წელი";
Calendar._TT["SEL_DATE"] = "ამოირჩიეთ თარიღი";
Calendar._TT["DRAG_TO_MOVE"] = "გადაადგილეთ";
Calendar._TT["PART_TODAY"] = " (ეხლა)";

// the following is to inform that "%s" is to be the first day of week %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "დავიწყოთ %sდან";

// This may be locale-dependent.  It specifies the week-end days, as an array of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1 means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";
Calendar._TT["CLOSE"] = "დახურვა";
Calendar._TT["TODAY"] = "ეხლა";
Calendar._TT["TIME_PART"] = "დააჭირეთ მნიშვნელობის შესაცვლელად";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";
Calendar._TT["WK"] = "კვ";
Calendar._TT["TIME"] = "დრო:";