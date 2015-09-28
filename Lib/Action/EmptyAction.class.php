<?php
/**
 * @author nanze
 * @link 
 * @todo 
 * @copyright 811046@qq.com
 * @version 1.0
 * @lastupdate 2010-11-24
 */
class EmptyAction extends CommAction {
	
	function _initialize() {
		parent::_initialize();
		Session::set('urlReferer', __SELF__);
	}
	
    function _empty() {
        $pathinfo=preg_replace("/(pid-|cid-){2,}/","$1",$_SERVER['PATH_INFO']);
        
        switch (true){
            case false!==strpos($pathinfo,'pid-'):
                $page=explode("pid-",$pathinfo);
                $_GET['pid']=$pid=intval($page[1]);
                $p=explode("-p-",$page[1]);
                if($p){
                    $_GET['p']=intval($p[1]);
                }
                $this->good($pid);
                break;
            case false!==strpos($pathinfo,'cid-'):
                $page=explode("cid-",$pathinfo);
                $_GET['cid']=$cid=intval($page[1]);
                $p=explode("-p-",$page[1]);
                if($p){
                    $_GET['p']=intval($p[1]);
                }
                $this->cate($cid);
                break;
            case 0==strcasecmp(MODULE_NAME,'art'):
                $this->article(ACTION_NAME);
                break;
            case 0==strcasecmp(MODULE_NAME,'Sitemap'):
                $this->sitemap();
                break;
            case 0==strcasecmp(MODULE_NAME,'Reviews'):
                $this->reviews();
                break;
            case substr($pathinfo,-1)=='/':
                $_GET['title']=$title=trim($pathinfo,'/');
                $this->article($title);
                break;
            default:
                parent::_empty();
                return;

        }

        return;
    }
    function good($pid) {
        $dao = D ( "Products" );
        if(isset($_POST['id'])){
            $pid=intval($_POST['id']);
        }
        $list = $dao->where ( "id=" . $pid )->find ();
        if ($list) {
            $dao->viewcounts ( $pid );
            //添加用户最近浏览的产品
            if(!isset($_SESSION['product_history']) ) {
                $_SESSION['product_history']=$pid;
            } else {

                if(!$broswer_history_num=GetValue('broswer_history_num')){
                    $broswer_history_num=100;
                }
                $history_array = explode(',',$_SESSION['product_history']);
                array_push($history_array,(int)$pid);
                $history_array = array_unique($history_array);
                if(count($history_array) > $broswer_history_num){
                    array_shift($history_array);
                }
                $_SESSION['product_history']=implode(',',$history_array);
            }

            //ajax属性价格
            if(isset($_POST['attr_id'])){
                $products_attr_model=D("Products_attr");
                $map['id']=array('in',$_POST['attr_id']);
                $products_attr=$products_attr_model->where($map)->select();
                $attrs=array();
                if($products_attr){
                    $attrs['price']=0;
                    foreach ($products_attr as $attr){
                        $attrs['price']+=$attr['attr_price'];
                    }
                    if($attrs['price']){
                        $attrs['product_price']=getprice_str(VipPrice($list['pricespe'])+$attrs['price']);
                        $attrs['attr_price']='('.getprice_str(VipPrice($list['pricespe'])).($attrs['price']>0?'+':'').getprice_str($attrs['price']).')';
                    }else{
                        $attrs['product_price']=getprice_str($list['pricespe']);
                        $attrs['attr_price']='';
                    }
                    die(json_encode($attrs));
                }
            }
            //产品主图、细节图片、详情图片   start
            $zoomcount = productzoompiccount($list);//  产品放大镜图片数量
            $list["zoomcount"] = $zoomcount;
            $tempzoompics = array();
            for($i=1;$i<=$zoomcount;$i++)
            {
                $tempzoompics[$i-1] = productzoompicpath($list,$i);

            }
            $list["tempzoompics"] = $tempzoompics;
            //    dump($tempzoompics);

            $detailcount =  productdetailpiccount($list);//产品详细图片数量
            $list["detailcount"] = $detailcount;
            $tempdetailpics = array();
            for($i=1;$i<=$detailcount;$i++)
            {
                $tempdetailpics[$i-1] = productdetailpicpath($list,$i);
            }
            $list["tempdetailpics"] = $tempdetailpics;
            //产品主图、细节图片、详情图片   end

            $this->list = $list;

            //获取Vip价格
            $this->vipPrice=VipPrice(get_real_price($list["price"],$list["pricespe"]));
            //上一产品，下一产品
            $prev=$dao->where(array("id"=>array("lt",$pid),'cateid'=>$list['cateid']))->order('id desc')->find();
            $next=$dao->where(array("id"=>array("gt",$pid),'cateid'=>$list['cateid']))->order('id asc')->find();
            if($prev){
                $this->prev=build_url($prev,'pro_url');
            }
            if($next){
                $this->next=build_url($next,'pro_url');
            }
            //获取产品属性
            $attrlist=$dao->get_attrs($list['cateid'],$pid);
            if(!$attrlist[0]['attrs']){
                $attrlist[0]['name']='size';
                $attrlist[0]['value']=array('nosize');
                $attrlist[0]['values_count']=1;
                $attrlist[0]['attrs']=array(0=>array('attr_value'=>'nosize'));
            }
            
            $this->attrlist=$attrlist;
            $this->attrcount=count($attrlist[0]['attrs']);


            if(!$realted_num=GetValue('realted_num')){
                $realted_num=6;
            }
            //相同尺码相同颜色
            $Pro_attrView=D('Pro_attrView');
            $att_values=implode(',',array_map('reset',$Pro_attrView->field('attr_value')->where(array('products_id'=>$pid))->select()));
            $attrs=array();
            foreach ($attrlist as $k=>$attr){
                $attrs[$k]['name']=$attr['name'];
                $attrs[$k]['pro']=$Pro_attrView->where(array('attr_value'=>array('in',$att_values),'cateid'=>$list['cateid']))->group('id')->limit(5)->select();
            }
            $this->attrs=$attrs;
            //获取同类产品,用户还买了什么产品
            $sameclass=$dao->where(array('cateid'=>$list['cateid']))->limit($realted_num)->select();
            $sameclass=$dao->rand($sameclass);
            //类别产品数量
            $this->cate_count=$dao->where(array('cateid'=>$list['cateid']))->count();
            //产品位置
            $this->postion=reset($dao->field('count(*)+1 as postion')->where("id<'".$pid."' and cateid='".$list['cateid']."'")->order('id desc')->find());
            //获取关联产品
            $Products_related_Model=D('Products_related');
            $related=$Products_related_Model->field('b.*')->table(C('DB_PREFIX').'products_related a')->join(C('DB_PREFIX').'products b on a.related_products_id=b.id')->where(array('a.products_id'=>$pid))->limit($realted_num)->select();
            empty($related)?$related=$sameclass:$related=$dao->rand($related);
            $this->related=$related;
            $this->sameclass=$sameclass;

            //欢迎词
            $dao= D('Ad');
            $this->welcome = $dao->where(array('remark'=>'产品内页欢迎词'))->getField('content');
            //获取产品
            //相册
            $dao = D ( "Products_gallery" );
            $gallerys = $dao->where ( "pid=" . $pid )->order ( "sort desc" )->select ();
            if(GetValue('auto_find_gallery')){
                if(file_exists(__ROOT__.$list['bigimage'])){
                    $bigimage=pathinfo(__ROOT__.$list['bigimage']);

                    $filename=$bigimage['filename'];
                    $dirname=$bigimage['dirname'];
                    $files=glob($dirname.'/'.$filename.'_*');

                    foreach ($files as $f){
                        $gallerys[]['img_url']=$f;
                    }
                }
            }
            $this->gallerys=$gallerys;



            $this->catep = get_catep_arr ( $list ['cateid'] );
            $classModel=D("Cate");
            //类别信息
            $this->cate=$cate=$classModel->where(array('id'=>$list['cateid']))->find();
            $classid=$list ['cateid'];
            $this->cateid=$classid;
            //SEO相关
            if (! empty ( $list ['pagekey'] ) || !$classid) {
                $this->assign ( 'pagekeywords',  $list ['pagekey'] );
            } else {
                $this->assign ( 'pagekeywords', $classModel->getKeywords ( $classid ) );
            }

            if (! empty ( $list ['pagedec'] ) || !$classid) {
                $this->assign ( 'pagedesc',  $list ['pagedec'] );
            } else {
                $this->assign ( 'pagedesc', $classModel->getDescription ( $classid ) );
            }
            if (! empty ( $list ['pagetitle'] ) || !$classid) {
                $this->assign ( 'pagetitle',  $list ['pagetitle'] );
            } else {
                $this->assign ( 'pagetitle', $classModel->getPageTitle ( $classid ) );
            }

            //评论数
            $products_ask=D('Products_ask');
            $this->review_num=$products_ask->where('products_id='.$pid)->count();
            $this->review_list=$products_ask->where('products_id='.$pid)->select();
            
            if(in_array($list['activity'],array('1'))){
                switch ($list['activity']){
                    case 1:
                        $PL_model=D('Products_lot');
                        $PL=array();

                        $PL['list']=$PL_model->where("product_id={$pid}")->order('mincount asc')->select();
                        if($PL['list']){
                            $PL['minprice']=$PL_model->where("product_id={$pid}")->order("maxcount desc")->getField('price');

                            $PL['maxprice']=$PL_model->where("product_id={$pid}")->order("mincount asc")->getField('price');
                            $this->assign("PL",$PL);
                            $this->display('Empty:disp_lot');
                            die;
                        }
                        break;
                }
            }
            $this->display("Empty:disp");

        } else {
            $this->redirect ( 'Index/index' );
        }

    }
    function cate($cid) {
        $this->catep = get_catep_arr ( $cid );//导航
        //获取下级分类
        $dao = D ( "Cate" );

        $this->assign('pagekeywords',$dao->getKeywords($cid));//关键字
        $this->assign('pagedesc',$dao->getDescription($cid));//描述
        $this->assign('pagetitle',$dao->getPageTitle($cid));//标题
        $strFid_class = 'Fid_class' . $cid;
        if (S ( $strFid_class ) == '') {
            $classChildren = $dao->getChildren ( $cid );
            $classChildren [count ( $classChildren )] = $cid;
            S ( $strFid_class, implode ( ",", $classChildren ) ); //取得所有子类
        }

        $map['isdown']=array('neq',1);
        $map['cateid']=array ('in', S ( $strFid_class ));
        switch (true){
            case isset($_REQUEST['new']):
                $map['isnew']=1;
                break;
            case isset($_REQUEST['hot']):
                $map['ishot']=1;
                break;
            case isset($_REQUEST['rec']):
                $map['isrec']=1;
                break;
            case isset($_REQUEST['price']):
                $map['isprice']=1;
                break;
        }
        //属性筛选
        if(isset($_GET['attr_id']) && isset($_GET['attr_value'])){
            $Pro_attrView=D('Pro_attrView');
            $map['attr_id']=intval($_GET['attr_id']);
            $map['attr_value']=base64_decode($_GET['attr_value']);
            $Pro_attrView->_list ( $this->view, $map, 'sort',false );
        }else{
            D("Products")->_list ( $this->view, $map, 'sort',false );
        }
        //属性筛选
        $typeid=$dao->getTypeId($cid);
        if($typeid){
            self::$Model=D("Type_attr");
            $attr=self::$Model->getAttrs($typeid);
            $this->attr=$attr;
        }
        $cateinfo = get_cate_info ( $cid );
        $catec = $dao->where ( "pid=" . $cid )->order ( "sort desc" )->select ();//下级分类
        //如果没有下级仍显示同类
        if(!$catec){
            $pid=$cateinfo['pid'];
            $catec=$dao->where ( "pid=" . $pid )->order ( "sort desc" )->select ();
        }
        $this->catec=$catec;
        $this->cateinfo = $cateinfo;
        $this->pagetitle=$cateinfo['pagetitle'];
        $this->pagekeywords=$cateinfo['pagekey'];
        $this->pagedesc=$cateinfo['pagedec'];
        $this->cateid=$cid;
        $this->display("Empty:cate");
    }
    function article($aid){
        
        parent::$Model=D('Article');

        if(is_numeric($aid)){
            $list=parent::$Model->where($map)->find($aid);
        }else{

            $title=auto_charset($aid,'utf-8','gbk');

            if(strcasecmp($title,'sitemap')==0){
                $this->sitemap();
                return;
            }

            if(strcasecmp($title,'contact-us')==0){
                $this->contact_us();
                return;
            }
            if(strcasecmp($title,'FAQs')==0){
                $this->FAQs();
                return;
            }
            if(strcasecmp($title,'News')==0){
                $this->News();
                return;
            }


            $article_cache=md5('article_'.$title);
            if(S($article_cache)==''){
                $map['title']=$title;

                $map['title2']=$title;//自定义文章URL用
                $map['_logic']='or';
                $list=parent::$Model->where($map)->find();

                //副标题
                if(empty($list['title2'])){
                    $list['title2']=$list['title'];
                }
                $list?$list['title']=str_replace("-"," ",$list['title']):'';
                S($article_cache,$list);
            }
            if(!empty($list['href'])){
                redirect($list['href']);
                die;
            }
            $list= S($article_cache);

        }
        if($list){
            $this->assign($list);
            $this->pagetitle=$list['title'];
            $this->pagekeywords=$list['keywords'];
            $this->pagedesc=$list['description'];
            $this->display("Empty:Article");
        }else{
            parent::_empty();
            return;
        }

    }

    function News(){
        $this->disp_text="News";
        self::$Model=D('Article_cate');
        $pid=self::$Model->where(array('name'=>'新闻中心'))->getField("id");
        $map['pid']=$pid;
        $this->name="新闻中心";
        self::$Model=D('Article');
        self::$Model->_list($this->view,$map,'id',false);
        $this->display ('Article:Article_list');
    }
    function sitemap(){
        $this->pagetitle='Sitemap';
        $this->pagekeywords='Sitemap';
        $this->pagedesc='Sitemap';
        $this->display("Empty:sitemap");
    }
    function Reviews(){
        Session::set( 'urlReferer', __SELF__);
        
        self::$Model=D("Products_ask");
        $this->disp_text="Reviews";
        if(isset($_REQUEST['id'])){
            $map['products_id']=$_REQUEST['id'];
        }
        self::$Model->_list($this->view,$map,'dateline',false);
        $this->display("Empty:reviews");
    }
    function FAQs(){
        $article_cache=md5('article_faqs');
        if(S($article_cache)==''){
            parent::$Model=D('Article_cate');
            $map['name']='FAQs';
            $pid=parent::$Model->where($map)->getField('id');

            parent::$Model=D('Article');
            $map1['pid']=$pid;
            $list['faq']=parent::$Model->where($map1)->select();
            S($article_cache,$list);
        }
        $list= S($article_cache);
        $this->assign($list);
        $this->pagetitle=$list['title'];
        $this->pagekeywords=$list['keywords'];
        $this->pagedesc=$list['description'];
        $this->display("Empty:FAQs");
    }
    function contact_us(){
        $article_cache=md5('article_conatct_us');
        if(S($article_cache)==''){
            parent::$Model=D('Article');
            $map['title']='Contact-us';
            $list=parent::$Model->where($map)->find();
            $list['title']=str_replace("-"," ",$list['title']);
            S($article_cache,$list);
        }
        $list= S($article_cache);

        $this->assign($list);
        $this->pagetitle=$list['title'];
        $this->pagekeywords=$list['keywords'];
        $this->pagedesc=$list['description'];
        $this->display("Empty:contact_us");
    }
}
?>