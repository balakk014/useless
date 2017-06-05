/* jssor.core.js */
$Jssor$=window.$Jssor$=window.$Jssor$||{};
    
var $JssorDebug$=new function(){
    this.$DebugMode=!0,this.$Log=function(e,t){
        var n=window.console||{},r=this.$DebugMode;
        r&&n.log?n.log(e):r&&t&&alert(e)
        },this.$Error=function(e,t){
        var n=window.console||{},r=this.$DebugMode;
        if(r&&n.error?n.error(e):r&&alert(e),r)throw t||new Error(e)
            },this.$Fail=function(e){
        throw new Error(e)
        },this.$Assert=function(e,t){
        var n=this.$DebugMode;
        if(n&&!e)throw new Error("Assert failed "+t||"")
            },this.$Trace=function(e){
        var t=window.console||{},n=this.$DebugMode;
        n&&t.log&&t.log(e)
        },this.$Execute=function(e){
        var t=this.$DebugMode;
        t&&e()
        },this.$LiveStamp=function(e,t){
        var n=document.createElement("DIV");
        n.setAttribute("id",t),e.$Live=n
        }
    },$JssorEventManager$=function(){
    var e=this,t={};
    
    e.$On=e.addEventListener=function(e,n){
        "function"==typeof n&&(t[e]||(t[e]=[]),t[e].push(n))
        },e.$Off=e.removeEventListener=function(e,n){
        var r=t[e];
        if("function"==typeof n&&r)for(var s=0;s<r.length;s++)if(n==r[s])return void r.splice(s,1)
            },e.$ClearEventListeners=function(e){
        t[e]&&delete t[e]
    },e.$TriggerEvent=function(e){
        var n=t[e],r=[];
        if(n){
            for(var s=1;s<arguments.length;s++)r.push(arguments[s]);
            for(var s=0;s<n.length;s++)try{
                n[s].apply(window,r)
                }catch(o){
                $JssorDebug$.$Error(o.name+" while executing "+e+" handler: "+o.message,o)
                }
            }
        }
};

jQuery(document).ready(function(){
    var e={
        $AutoPlay:!0,
        $AutoPlaySteps:1,
        $AutoPlayInterval:4e3,
        $PauseOnHover:0,
        $ArrowKeyNavigation:!0,
        $SlideDuration:500,
        $MinDragOffsetToSlide:20,
        $SlideWidth:212,
        $SlideSpacing:10,
        $DisplayPieces:5,
        $ParkingPosition:0,
        $UISearchMode:1,
        $PlayOrientation:1,
        $DragOrientation:1,
        $FillMode:4,
        $LazyLoading:3,
        $ArrowNavigatorOptions:{
            $Class:$JssorArrowNavigator$,
            $ChanceToShow:2,
            $AutoCenter:2,
            $Steps:1
        }
    },t={
    $AutoPlay:!0,
    $AutoPlaySteps:4,
    $AutoPlayInterval:4e3,
    $PauseOnHover:0,
    $ArrowKeyNavigation:!0,
    $SlideDuration:500,
    $MinDragOffsetToSlide:20,
    $SlideWidth:200,
    $SlideSpacing:20,
    $DisplayPieces:8,
    $ParkingPosition:0,
    $UISearchMode:1,
    $PlayOrientation:1,
    $DragOrientation:1,
    $FillMode:4,
    $LazyLoading:3,
    $ArrowNavigatorOptions:{
        $Class:$JssorArrowNavigator$,
        $ChanceToShow:2,
        $AutoCenter:2,
        $Steps:4
    }
},n={
    $AutoPlay:!0,
    $AutoPlaySteps:1,
    $AutoPlayInterval:4e3,
    $PauseOnHover:0,
    $ArrowKeyNavigation:!0,
    $SlideDuration:300,
    $MinDragOffsetToSlide:20,
    $SlideWidth:200,
    $SlideSpacing:20,
    $DisplayPieces:1,
    $ParkingPosition:0,
    $UISearchMode:1,
    $PlayOrientation:1,
    $DragOrientation:1,
    $FillMode:4,
    $LazyLoading:3,
    $ArrowNavigatorOptions:{
        $Class:$JssorArrowNavigator$,
        $ChanceToShow:2,
        $AutoCenter:2,
        $Steps:1
    }
};

})
  
/* jssor.slider.js */
function $JssorCaptionSliderBase$(){
    $JssorAnimator$.call(this,0,0);
    this.$Revert=$JssorUtils$.$EmptyFunction
    }
    var $JssorSlider$;
var $JssorSlideshowFormations$=window.$JssorSlideshowFormations$={};

var $JssorSlideshowRunner$;
new function(){
    function S(e){
        return(e&o)==o
        }
        function x(e){
        return(e&u)==u
        }
        function T(e){
        return(e&a)==a
        }
        function N(e){
        return(e&f)==f
        }
        function C(e,t,n){
        n.push(t);
        e[t]=e[t]||[];
        e[t].push(n)
        }
        function k(e){
        var t=e.$Formation(e);
        return e.$Reverse?t.reverse():t
        }
        function L(e,t){
        var n={
            $Interval:t,
            $Duration:1,
            $Delay:0,
            $Cols:1,
            $Rows:1,
            $Opacity:0,
            $Zoom:0,
            $Clip:0,
            $Move:false,
            $SlideOut:false,
            $FlyDirection:0,
            $Reverse:false,
            $Formation:$JssorSlideshowFormations$.$FormationRandom,
            $Assembly:E,
            $ChessMode:{
                $Column:0,
                $Row:0
            },
            $Easing:$JssorEasing$.$EaseSwing,
            $Round:{},
            $Blocks:[],
            $During:{}
    };
    
    $JssorUtils$.$Extend(n,e);
    n.$Count=n.$Cols*n.$Rows;
    if($JssorUtils$.$IsFunction(n.$Easing))n.$Easing={
        $Default:n.$Easing
        };
        
    n.$FramesCount=Math.ceil(n.$Duration/n.$Interval);
    n.$EasingInstance=A(n);
    n.$GetBlocks=function(e,t){
        e/=n.$Cols;
        t/=n.$Rows;
        var r=e+"x"+t;
        if(!n.$Blocks[r]){
            n.$Blocks[r]={
                $Width:e,
                $Height:t
            };
            
            for(var i=0;i<n.$Cols;i++){
                for(var s=0;s<n.$Rows;s++)n.$Blocks[r][s+","+i]={
                    $Top:s*t,
                    $Right:i*e+e,
                    $Bottom:s*t+t,
                    $Left:i*e
                    }
                }
            }
        return n.$Blocks[r]
};

if(n.$Brother){
    n.$Brother=L(n.$Brother,t);
    n.$SlideOut=true
    }
    return n
}
function A(e){
    var t=e.$Easing;
    if(!t.$Default)t.$Default=$JssorEasing$.$EaseSwing;
    var n=e.$FramesCount;
    var r=t.$Cache;
    if(!r){
        var i=$JssorUtils$.$Extend({},e.$Easing,e.$Round);
        r=t.$Cache={};
        
        $JssorUtils$.$Each(i,function(i,s){
            var o=t[s]||t.$Default;
            var u=e.$Round[s]||1;
            if(!$JssorUtils$.$IsArray(o.$Cache))o.$Cache=[];
            var a=o.$Cache[n]=o.$Cache[n]||[];
            if(!a[u]){
                a[u]=[0];
                for(var f=1;f<=n;f++){
                    var l=f/n*u;
                    var c=Math.floor(l);
                    if(l!=c)l-=c;
                    a[u][f]=o(l)
                    }
                }
                r[s]=a
        })
    }
    return r
}
function O(e,t,n,r,i,s){
    function x(e){
        $JssorUtils$.$DisableHWA(e);
        var t=$JssorUtils$.$GetChildren(e);
        $JssorUtils$.$Each(t,function(e){
            x(e)
            })
        }
        var o=this;
    var u;
    var a={};
    
    var f={};
    
    var l=[];
    var c;
    var h;
    var p;
    var d=n.$ChessMode.$Column||0;
    var v=n.$ChessMode.$Row||0;
    var m=n.$GetBlocks(i,s);
    var g=k(n);
    var y=g.length-1;
    var b=n.$Duration+n.$Delay*y;
    var w=r+b;
    var E=n.$SlideOut;
    var S;
    w+=$JssorUtils$.$IsBrowserChrome()?260:50;
    o.$EndTime=w;
    o.$ShowFrame=function(e){
        e-=r;
        var t=e<b;
        if(t||S){
            S=t;
            if(!E)e=b-e;
            var i=Math.ceil(e/n.$Interval);
            $JssorUtils$.$Each(f,function(e,t){
                var n=Math.max(i,e.$Min);
                n=Math.min(n,e.length-1);
                if(e.$LastFrameIndex!=n){
                    if(!e.$LastFrameIndex&&!E){
                        $JssorUtils$.$ShowElement(l[t])
                        }else if(n==e.$Max&&E){
                        $JssorUtils$.$HideElement(l[t])
                        }
                        e.$LastFrameIndex=n;
                    $JssorUtils$.$SetStylesEx(l[t],e[n])
                    }
                })
        }
    };

{
    t=$JssorUtils$.$CloneNode(t,true);
    x(t);
    if($JssorUtils$.$IsBrowserIe9Earlier()){
        var T=!t["no-image"];
        var N=$JssorUtils$.$FindChildrenByTag(t,null,true);
        $JssorUtils$.$Each(N,function(e){
            if(T||e["jssor-slider"])$JssorUtils$.$SetStyleOpacity(e,$JssorUtils$.$GetStyleOpacity(e),true)
                })
        }
        $JssorUtils$.$Each(g,function(e,t){
        $JssorUtils$.$Each(e,function(e){
            var r=e[0];
            var o=e[1];
            {
                var l=r+","+o;
                var g=false;
                var y=false;
                var b=false;
                if(d&&o%2){
                    if($JssorDirection$.$IsHorizontal(d)){
                        g=!g
                        }
                        if($JssorDirection$.$IsVertical(d)){
                        y=!y
                        }
                        if(d&16)b=!b
                        }
                        if(v&&r%2){
                    if($JssorDirection$.$IsHorizontal(v)){
                        g=!g
                        }
                        if($JssorDirection$.$IsVertical(v)){
                        y=!y
                        }
                        if(v&16)b=!b
                        }
                        n.$Top=n.$Top||n.$Clip&4;
                n.$Bottom=n.$Bottom||n.$Clip&8;
                n.$Left=n.$Left||n.$Clip&1;
                n.$Right=n.$Right||n.$Clip&2;
                var w=y?n.$Bottom:n.$Top;
                var S=y?n.$Top:n.$Bottom;
                var x=g?n.$Right:n.$Left;
                var T=g?n.$Left:n.$Right;
                n.$Clip=w||S||x||T;
                p={};
                
                h={
                    $Top:0,
                    $Left:0,
                    $Opacity:1,
                    $Width:i,
                    $Height:s
                };
                
                c=$JssorUtils$.$Extend({},h);
                u=$JssorUtils$.$Extend({},m[l]);
                if(n.$Opacity){
                    h.$Opacity=2-n.$Opacity
                    }
                    if(n.$ZIndex){
                    h.$ZIndex=n.$ZIndex;
                    c.$ZIndex=0
                    }
                    var N=n.$Cols*n.$Rows>1||n.$Clip;
                if(n.$Zoom||n.$Rotate){
                    var C=true;
                    if($JssorUtils$.$IsBrowserIE()&&$JssorUtils$.$GetBrowserEngineVersion()<9){
                        if(n.$Cols*n.$Rows>1)C=false;else N=false
                            }
                            if(C){
                        h.$Zoom=n.$Zoom?n.$Zoom-1:1;
                        c.$Zoom=1;
                        if($JssorUtils$.$IsBrowserIe9Earlier()||$JssorUtils$.$IsBrowserOpera())h.$Zoom=Math.min(h.$Zoom,2);
                        var k=n.$Rotate;
                        if(k==true)k=1;
                        h.$Rotate=k*360*(b?-1:1);
                        c.$Rotate=0
                        }
                    }
                if(N){
                if(n.$Clip){
                    var L=n.$ScaleClip||1;
                    var A=u.$Offset={};
                    
                    if(w&&S){
                        A.$Top=m.$Height/2*L;
                        A.$Bottom=-A.$Top
                        }else if(w){
                        A.$Bottom=-m.$Height*L
                        }else if(S){
                        A.$Top=m.$Height*L
                        }
                        if(x&&T){
                        A.$Left=m.$Width/2*L;
                        A.$Right=-A.$Left
                        }else if(x){
                        A.$Right=-m.$Width*L
                        }else if(T){
                        A.$Left=m.$Width*L
                        }
                    }
                p.$Clip=u;
            c.$Clip=m[l]
            }
            if(n.$FlyDirection){
            var O=n.$FlyDirection;
            if(!g)O=$JssorDirection$.$ChessHorizontal(O);
            if(!y)O=$JssorDirection$.$ChessVertical(O);
            var M=n.$ScaleHorizontal||1;
            var _=n.$ScaleVertical||1;
            if($JssorDirection$.$IsToLeft(O)){
                h.$Left+=i*M
                }else if($JssorDirection$.$IsToRight(O)){
                h.$Left-=i*M
                }
                if($JssorDirection$.$IsToTop(O)){
                h.$Top+=s*_
                }else if($JssorDirection$.$IsToBottom(O)){
                h.$Top-=s*_
                }
            }
        $JssorUtils$.$Each(h,function(e,t){
            if($JssorUtils$.$IsNumeric(e)){
                if(e!=c[t]){
                    p[t]=e-c[t]
                    }
                }
        });
    a[l]=E?c:h;
    var D=[];
    var P=Math.round(t*n.$Delay/n.$Interval);
    f[l]=new Array(P);
    f[l].$Min=P;
    var H=n.$FramesCount;
    for(var B=0;B<=H;B++){
        var j={};
        
        $JssorUtils$.$Each(p,function(e,t){
            var r=n.$EasingInstance[t]||n.$EasingInstance.$Default;
            var i=r[n.$Round[t]||1];
            var s=n.$During[t]||[0,1];
            var o=(B/H-s[0])/s[1]*H;
            o=Math.round(Math.min(H,Math.max(o,0)));
            var u=i[o];
            if($JssorUtils$.$IsNumeric(e)){
                j[t]=c[t]+e*u
                }else{
                var a=j[t]=$JssorUtils$.$Extend({},c[t]);
                a.$Offset=[];
                $JssorUtils$.$Each(e.$Offset,function(e,t){
                    var n=e*u;
                    a.$Offset[t]=n;
                    a[t]+=n
                    })
                }
            });
    if(c.$Zoom){
        j.$Transform={
            $Rotate:j.$Rotate||0,
            $Scale:j.$Zoom,
            $OriginalWidth:i,
            $OriginalHeight:s
        }
    }
    if(j.$Clip&&n.$Move){
        var F=j.$Clip.$Offset;
        var I=(F.$Top||0)+(F.$Bottom||0);
        var q=(F.$Left||0)+(F.$Right||0);
        j.$Left=(j.$Left||0)+q;
        j.$Top=(j.$Top||0)+I;
        j.$Clip.$Left-=q;
        j.$Clip.$Right-=q;
        j.$Clip.$Top-=I;
        j.$Clip.$Bottom-=I
        }
        j.$ZIndex=j.$ZIndex||1;
    f[l].push(j)
    }
}
})
});
g.reverse();
$JssorUtils$.$Each(g,function(n){
    $JssorUtils$.$Each(n,function(n){
        var r=n[0];
        var i=n[1];
        var s=r+","+i;
        var o=t;
        if(i||r)o=$JssorUtils$.$CloneNode(t,true);
        $JssorUtils$.$SetStyles(o,a[s]);
        $JssorUtils$.$SetStyleOverflow(o,"hidden");
        $JssorUtils$.$SetStylePosition(o,"absolute");
        e.$AddClipElement(o);
        l[s]=o;
        $JssorUtils$.$ShowElement(o,E)
        })
    })
}
}
function _(e,t){
    function r(){
        var e=this;
        $JssorAnimator$.call(e,-1e8,2e8);
        e.$GetCurrentSlideInfo=function(){
            var t=e.$GetPosition_Display();
            var n=Math.floor(t);
            var r=E(n);
            var i=t-Math.floor(t);
            return{
                $Index:r,
                $VirtualIndex:n,
                $Position:i
            }
        };
        
    e.$OnPositionChange=function(e,t){
        var r=Math.floor(t);
        if(r!=t&&t>e)r++;
        g(r,true);
        n.$TriggerEvent(_.$EVT_POSITION_CHANGE,E(t),E(e),t,e)
        }
    }
function i(){
    var e=this;
    $JssorAnimator$.call(e,0,0,{
        $LoopLength:nt
    });
    {
        $JssorUtils$.$Each(Gt,function(t){
            _t&&t.$SetLoopLength(nt);
            e.$Chain(t);
            t.$Shift(Ot/at)
            })
        }
    }
function s(){
    var e=this;
    var t=Jt.$Elmt;
    $JssorAnimator$.call(e,-1,2,{
        $Easing:$JssorEasing$.$EaseLinear,
        $Setter:{
            $Position:l
        },
        $LoopLength:nt
    },t,{
        $Position:1
    },{
        $Position:-1
    });
    e.$Wrapper=t;
    {
        $JssorDebug$.$Execute(function(){
            $JssorUtils$.$SetAttribute(Jt.$Elmt,"debug-id","slide_container")
            })
        }
    }
function o(e,t){
    var i=this;
    var s;
    var o;
    var u;
    var a;
    var f;
    $JssorAnimator$.call(i,-1e8,2e8);
    i.$OnStart=function(){
        Bt=true;
        Ft=null;
        n.$TriggerEvent(_.$EVT_SWIPE_START,E(Xt.$GetPosition()),Xt.$GetPosition())
        };
        
    i.$OnStop=function(){
        Bt=false;
        a=false;
        var e=Xt.$GetCurrentSlideInfo();
        n.$TriggerEvent(_.$EVT_SWIPE_END,E(Xt.$GetPosition()),Xt.$GetPosition());
        if(!e.$Position){
            m(e.$VirtualIndex,Z)
            }
        };
    
i.$OnPositionChange=function(e,t){
    var n;
    if(a)n=f;
    else{
        n=o;
        if(u)n=P.$SlideEasing(t/u)*(o-s)+s
            }
            Xt.$GoToPosition(n)
    };
    
i.$PlayCarousel=function(e,t,n,r){
    $JssorDebug$.$Execute(function(){
        if(i.$IsPlaying())$JssorDebug$.$Fail("The carousel is already playing.")
            });
    s=e;
    o=t;
    u=n;
    Xt.$GoToPosition(e);
    i.$GoToPosition(0);
    i.$PlayToPosition(n,r)
    };
    
i.$StandBy=function(e){
    a=true;
    f=e;
    i.$Play(e,null,true)
    };
    
i.$SetStandByPosition=function(e){
    f=e
    };
    
i.$MoveCarouselTo=function(e){
    Xt.$GoToPosition(e)
    };
    
{
    Xt=new r;
    Xt.$Combine(e);
    Xt.$Combine(t)
    }
}
function u(){
    var e=this;
    var t=w();
    $JssorUtils$.$SetStyleZIndex(t,0);
    e.$Elmt=t;
    e.$AddClipElement=function(e){
        $JssorUtils$.$AppendChild(t,e);
        $JssorUtils$.$ShowElement(t)
        };
        
    e.$Clear=function(){
        $JssorUtils$.$HideElement(t);
        $JssorUtils$.$ClearInnerHtml(t)
        }
    }
function a(e,t){
    function C(t){
        s&&s.$Revert();
        i&&i.$Revert();
        R(e,t);
        i=new B.$Class(e,B,1);
        $JssorDebug$.$LiveStamp(i,"caption_slider_"+D+"_in");
        s=new B.$Class(e,B);
        $JssorDebug$.$LiveStamp(s,"caption_slider_"+D+"_out");
        $JssorDebug$.$Execute(function(){
            D++
        });
        s.$GoToBegin();
        i.$GoToBegin()
        }
        function k(){
        if(i.$Version<B.$Version){
            C()
            }
        }
    function L(e,t,i){
    if(!y){
        y=true;
        if(p&&i){
            var s=i.width;
            var o=i.height;
            var u=s;
            var a=o;
            if(s&&o&&P.$FillMode){
                if(P.$FillMode&3){
                    var f=false;
                    var l=rt/it*o/s;
                    if(P.$FillMode&1){
                        f=l>1
                        }else if(P.$FillMode&2){
                        f=l<1
                        }
                        u=f?s*it/o:rt;
                    a=f?it:o*rt/s
                    }
                    $JssorUtils$.$SetStyleWidth(p,u);
                $JssorUtils$.$SetStyleHeight(p,a);
                $JssorUtils$.$SetStyleTop(p,(it-a)/2);
                $JssorUtils$.$SetStyleLeft(p,(rt-u)/2)
                }
                $JssorUtils$.$SetStylePosition(p,"absolute");
            n.$TriggerEvent(_.$EVT_LOAD_END,pn)
            }
        }
    $JssorUtils$.$HideElement(t);
e&&e(r)
}
function A(e,n,i,s){
    if(s==Ft&&Z==t&&St){
        if(!Et){
            var o=E(e);
            Yt.$Initialize(o,t,n,r,i);
            n.$HideContentForSlideshow();
            Vt.$Locate(o,1);
            Vt.$GoToPosition(o);
            $t.$PlayCarousel(e,e,0)
            }
        }
}
function M(e){
    if(e==Ft&&Z==t){
        if(!S){
            var n=null;
            if(Yt){
                if(Yt.$Index==t)n=Yt.$GetProcessor();else Yt.$Clear()
                    }
                    k();
            S=new f(t,n,r.$GetCaptionSliderIn(),r.$GetCaptionSliderOut());
            S.$SetPlayer(T)
            }!S.$IsPlaying()&&S.$Replay()
        }
    }
function H(e,n){
    if(e==t){
        if(e!=n)Gt[n]&&Gt[n].$ParkOut();
        T&&T.$Enable();
        var i=Ft=$JssorUtils$.$GetNow();
        r.$LoadImage($JssorUtils$.$CreateCallback(null,M,i))
        }else{
        var s=Math.abs(t-e);
        if(!b||s<=P.$LazyLoading||nt-s<=P.$LazyLoading){
            r.$LoadImage()
            }
        }
}
function j(){
    if(Z==t&&S){
        S.$Stop();
        T&&T.$Quit();
        T&&T.$Disable();
        S.$OpenSlideshowPanel()
        }
    }
function F(){
    if(Z==t&&S){
        S.$Stop()
        }
    }
function I(e){
    if(Pt){
        $JssorUtils$.$CancelEvent(e)
        }else{
        n.$TriggerEvent(_.$EVT_CLICK,t,e)
        }
    }
function q(){
    T=x.pInstance;
    S&&S.$SetPlayer(T)
    }
    function R(e,t,n){
    n=n||0;
    if(!w){
        if(e.tagName=="IMG"){
            d.push(e);
            if(!e.src){
                b=true;
                e["display-origin"]=$JssorUtils$.$GetStyleDisplay(e);
                $JssorUtils$.$HideElement(e)
                }
            }
        if($JssorUtils$.$IsBrowserIe9Earlier()){
        $JssorUtils$.$SetStyleZIndex(e,$JssorUtils$.$GetStyleZIndex(e)+1)
        }
        if(P.$HWA&&$JssorUtils$.$GetWebKitVersion()>0){
        if(!vt||$JssorUtils$.$GetWebKitVersion()<534||!At){
            $JssorUtils$.$EnableHWA(e)
            }
        }
}
var r=$JssorUtils$.$GetChildren(e);
$JssorUtils$.$Each(r,function(r,i){
    var s=$JssorUtils$.$GetAttribute(r,"u");
    if(s=="player"&&!x){
        x=r;
        if(x.pInstance){
            q()
            }else{
            $JssorUtils$.$AddEvent(x,"dataavailable",q)
            }
        }
    if(s=="caption"){
    if(!$JssorUtils$.$IsBrowserIE()&&!t){
        var o=$JssorUtils$.$CloneNode(r,true);
        $JssorUtils$.$InsertBefore(e,o,r);
        $JssorUtils$.$RemoveChild(e,r);
        r=o;
        t=true
        }
    }else if(!w&&!n&&!p&&$JssorUtils$.$GetAttribute(r,"u")=="image"){
    p=r;
    if(p){
        if(p.tagName=="A"){
            v=p;
            $JssorUtils$.$SetStyles(v,J);
            m=$JssorUtils$.$CloneNode(p,false);
            $JssorUtils$.$AddEvent(m,"click",I);
            $JssorUtils$.$SetStyles(m,J);
            $JssorUtils$.$SetStyleDisplay(m,"block");
            $JssorUtils$.$SetStyleOpacity(m,0);
            $JssorUtils$.$SetStyleBackgroundColor(m,"#000");
            p=$JssorUtils$.$FindFirstChildByTag(p,"IMG");
            $JssorDebug$.$Execute(function(){
                if(!p){
                    $JssorDebug$.$Error("slide html code definition error, no 'IMG' found in a 'image with link' slide.\r\n"+e.outerHTML)
                    }
                })
        }
        p.border=0;
    $JssorUtils$.$SetStyles(p,J)
    }
}
R(r,t,n+1)
})
}
var r=this;
var i;
var s;
var o;
var u;
var a;
var c=e;
var h;
var p;
var d=[];
var v;
var m;
var g;
var y;
var b;
var w;
var S;
var x;
var T;
var N;
$JssorAnimator$.call(r,-ft,ft+1,{
    $SlideItemAnimator:true
});
r.$LoadImage=function(e,t){
    t=t||h;
    if(d.length&&!y){
        $JssorUtils$.$ShowElement(t);
        if(!g){
            g=true;
            n.$TriggerEvent(_.$EVT_LOAD_START);
            $JssorUtils$.$Each(d,function(e){
                if(!e.src){
                    e.src=$JssorUtils$.$GetAttribute(e,"src2");
                    $JssorUtils$.$SetStyleDisplay(e,e["display-origin"])
                    }
                })
        }
        $JssorUtils$.$LoadImages(d,p,$JssorUtils$.$CreateCallback(null,L,e,t))
    }else{
    L(e,t)
    }
};

r.$GoForNextSlide=function(){
    if(Yt){
        var e=Yt.$GetTransition(nt);
        if(e){
            var n=Ft=$JssorUtils$.$GetNow();
            var r=t+1;
            var i=Gt[E(r)];
            return i.$LoadImage($JssorUtils$.$CreateCallback(null,A,r,i,e,n),h)
            }
        }
    O(Z+P.$AutoPlaySteps)
};

r.$TryActivate=function(){
    H(t,t)
    };
    
r.$ParkOut=function(){
    T&&T.$Quit();
    T&&T.$Disable();
    r.$UnhideContentForSlideshow();
    S&&S.$Abort();
    S=null;
    C()
    };
    
r.$StampSlideItemElements=function(t){
    t=N+"_"+t;
    $JssorDebug$.$Execute(function(){
        if(p)$JssorUtils$.$SetAttribute(p,"debug-id",t+"_slide_item_image_id");
        $JssorUtils$.$SetAttribute(e,"debug-id",t+"_slide_item_item_id")
        });
    $JssorDebug$.$Execute(function(){
        $JssorUtils$.$SetAttribute(a,"debug-id",t+"_slide_item_wrapper_id")
        });
    $JssorDebug$.$Execute(function(){
        $JssorUtils$.$SetAttribute(h,"debug-id",t+"_loading_container_id")
        })
    };
    
r.$HideContentForSlideshow=function(){
    $JssorUtils$.$HideElement(e)
    };
    
r.$UnhideContentForSlideshow=function(){
    $JssorUtils$.$ShowElement(e)
    };
    
r.$EnablePlayer=function(){
    T&&T.$Enable()
    };
    
r.$OnInnerOffsetChange=function(e,t){
    var n=ft-t;
    l(a,n)
    };
    
r.$GetCaptionSliderIn=function(){
    return i
    };
    
r.$GetCaptionSliderOut=function(){
    return s
    };
    
r.$Index=t;
$JssorEventManager$.call(r);
{
    var U=$JssorUtils$.$FindFirstChildByAttribute(e,"thumb");
    if(U){
        r.$Thumb=$JssorUtils$.$CloneNode(U,true);
        $JssorUtils$.$HideElement(U)
        }
        $JssorUtils$.$ShowElement(e);
    h=$JssorUtils$.$CloneNode(z,true);
    $JssorUtils$.$SetStyleZIndex(h,1e3);
    $JssorUtils$.$AddEvent(e,"click",I);
    C(true);
    w=true;
    r.$Image=p;
    r.$Link=m;
    r.$Item=e;
    r.$Wrapper=a=e;
    $JssorUtils$.$AppendChild(a,h);
    n.$On(203,H);
    n.$On(22,F);
    n.$On(24,j);
    $JssorDebug$.$Execute(function(){
        N=Q++
    });
    $JssorDebug$.$Execute(function(){
        $JssorUtils$.$SetAttribute(a,"debug-id","slide-"+t)
        })
    }
}
function f(e,t,r,i){
    function y(){
        $JssorUtils$.$ClearChildren(Zt);
        if(wt&&p&&g.$Link){
            $JssorUtils$.$AppendChild(Zt,g.$Link)
            }
            $JssorUtils$.$ShowElement(Zt,p||!g.$Image)
        }
        function b(){
        if(d){
            d=false;
            n.$TriggerEvent(_.$EVT_ROLLBACK_END,e,c,o,l,c,h);
            s.$GoToPosition(l)
            }
            s.$Replay()
        }
        function w(e){
        m=e;
        s.$Stop();
        s.$Replay()
        }
        var s=this;
    var o=0;
    var u=0;
    var a;
    var f;
    var l;
    var c;
    var h;
    var p;
    var d;
    var v;
    var m;
    var g=Gt[e];
    $JssorAnimator$.call(s,0,0);
    s.$Replay=function(){
        var t=s.$GetPosition_Display();
        if(!jt&&!Bt&&!m&&(t!=c||St&&(!Tt||Ht))&&Z==e){
            if(!t){
                if(a&&!p){
                    p=true;
                    s.$OpenSlideshowPanel(true);
                    n.$TriggerEvent(_.$EVT_SLIDESHOW_START,e,o,u,a,h)
                    }
                    y()
                }
                var r;
            var i=_.$EVT_STATE_CHANGE;
            if(t==h){
                if(c==h)s.$GoToPosition(l);
                return g.$GoForNextSlide()
                }else if(t==c){
                r=h
                }else if(t==l){
                r=c
                }else if(!t){
                r=l
                }else if(t>c){
                d=true;
                r=c;
                i=_.$EVT_ROLLBACK_START
                }else{
                r=s.$GetPlayToPosition()
                }
                n.$TriggerEvent(i,e,t,o,l,c,h);
            s.$PlayToPosition(r,b)
            }
        };
    
s.$Abort=function(){
    Yt&&Yt.$Index==e&&Yt.$Clear();
    var t=s.$GetPosition_Display();
    if(t<h){
        n.$TriggerEvent(_.$EVT_STATE_CHANGE,e,-t-1,o,l,c,h)
        }
    };

s.$OpenSlideshowPanel=function(e){
    if(t){
        $JssorUtils$.$SetStyleOverflow(lt,e&&t.$Transition.$Outside?"":"hidden")
        }
    };

s.$OnInnerOffsetChange=function(t,r){
    if(p&&r>=a){
        p=false;
        y();
        g.$UnhideContentForSlideshow();
        Yt.$Clear();
        n.$TriggerEvent(_.$EVT_SLIDESHOW_END,e,o,u,a,h)
        }
        n.$TriggerEvent(_.$EVT_PROGRESS_CHANGE,e,r,o,l,c,h)
    };
    
s.$SetPlayer=function(e){
    if(e&&!v){
        v=e;
        e.$On($JssorPlayer$.$EVT_SWITCH,w)
        }
    };

{
    if(t){
        s.$Chain(t)
        }
        a=s.$GetPosition_OuterEnd();
    f=s.$GetPosition_OuterEnd();
    s.$Chain(r);
    l=r.$GetPosition_OuterEnd();
    c=l+P.$AutoPlayInterval;
    i.$Shift(c);
    s.$Combine(i);
    h=s.$GetPosition_OuterEnd()
    }
}
function l(e,t){
    var n=ht>0?ht:P.$PlayOrientation;
    var r=Math.round(ot*t*(n&1));
    var i=Math.round(ut*t*(n>>1&1));
    if($JssorUtils$.$IsBrowserIE()&&$JssorUtils$.$GetBrowserVersion()>=10&&$JssorUtils$.$GetBrowserVersion()<11){
        e.style.msTransform="translate("+r+"px, "+i+"px)"
        }else if($JssorUtils$.$IsBrowserChrome()&&$JssorUtils$.$GetBrowserVersion()>=30){
        e.style.WebkitTransition="transform 0s";
        e.style.WebkitTransform="translate3d("+r+"px, "+i+"px, 0px) perspective(2000px)"
        }else{
        $JssorUtils$.$SetStyleLeft(e,r);
        $JssorUtils$.$SetStyleTop(e,i)
        }
    }
function c(e){
    Pt=0;
    if(!pt&&y()){
        h(e)
        }
    }
function h(e){
    un=Bt;
    jt=true;
    dt=false;
    Ft=null;
    $JssorUtils$.$AddEvent(document,tn,p);
    sn=$JssorUtils$.$GetNow()-50;
    an=$t.$GetPlayToPosition();
    $t.$Stop();
    if(!un)ht=0;
    if(vt){
        var t=e.touches[0];
        It=t.clientX;
        qt=t.clientY
        }else{
        var r=$JssorUtils$.$GetMousePosition(e);
        It=r.x;
        qt=r.y;
        $JssorUtils$.$CancelEvent(e)
        }
        Rt=0;
    Ut=0;
    zt=0;
    on=Xt.$GetPosition();
    n.$TriggerEvent(_.$EVT_DRAG_START,E(on),on,e)
    }
    function p(e){
    if(jt&&(!$JssorUtils$.$IsBrowserIe9Earlier()||e.button)){
        var t;
        if(vt){
            var n=e.touches;
            if(n&&n.length>0){
                t=new $JssorPoint$(n[0].clientX,n[0].clientY)
                }
            }else{
        t=$JssorUtils$.$GetMousePosition(e)
        }
        if(t){
        var r=t.x-It;
        var i=t.y-qt;
        if(Math.floor(on)!=on)ht=P.$PlayOrientation&pt;
        if((r||i)&&!ht){
            if(pt==3){
                if(Math.abs(i)>Math.abs(r)){
                    ht=2
                    }else ht=1
                    }else{
                ht=pt
                }
                if(vt&&ht==1&&Math.abs(i)-Math.abs(r)>3){
                dt=true
                }
            }
        if(ht){
        var s=i;
        var o=ut;
        if(ht==1){
            s=r;
            o=ot
            }
            if(!_t){
            if(s>0){
                var u=o*Z;
                var a=s-u;
                if(a>0){
                    s=u+Math.sqrt(a)*5
                    }
                }
            if(s<0){
            var u=o*(nt-ft-Z);
            var a=-s-u;
            if(a>0){
                s=-u-Math.sqrt(a)*5
                }
            }
    }
if(Rt-Ut<-2){
    zt=1
    }else if(Rt-Ut>2){
    zt=0
    }
    Ut=Rt;
Rt=s;
fn=on-Rt/o/(Kt||1);
if(Rt&&ht&&!dt){
    $JssorUtils$.$CancelEvent(e);
    if(!Bt){
        $t.$StandBy(fn)
        }else $t.$SetStandByPosition(fn)
        }else if($JssorUtils$.$IsBrowserIe9Earlier()){
    $JssorUtils$.$CancelEvent(e)
    }
}
}
}else{
    d(e)
    }
}
function d(e){
    b();
    if(jt){
        jt=false;
        sn=$JssorUtils$.$GetNow();
        $JssorUtils$.$RemoveEvent(document,tn,p);
        Pt=Rt;
        Pt&&$JssorUtils$.$CancelEvent(e);
        $t.$Stop();
        var t=Xt.$GetPosition();
        n.$TriggerEvent(_.$EVT_DRAG_END,E(t),t,E(on),on,e);
        var r=Math.floor(on);
        if(Math.abs(Rt)>=P.$MinDragOffsetToSlide){
            r=Math.floor(t);
            r+=zt
            }
            if(!_t){
            r=Math.min(nt-ft,Math.max(r,0))
            }
            var i=Math.abs(r-t);
        i=1-Math.pow(1-i,5);
        if(!Pt&&un){
            $t.$Continue(an)
            }else if(t==r){
            tt.$EnablePlayer();
            tt.$TryActivate()
            }else{
            $t.$PlayCarousel(t,r,i*Ct)
            }
        }
}
function v(e){
    et=Gt[Z];
    Y=Z;
    Z=E(e);
    tt=Gt[Z];
    g(e);
    return Z
    }
    function m(e,t){
    ht=0;
    v(e);
    n.$TriggerEvent(_.$EVT_PARK,E(e),t)
    }
    function g(e,t){
    $JssorUtils$.$Each(mt,function(n){
        n.$SetCurrentIndex(E(e),e,t)
        })
    }
    function y(){
    var e=_.$DragRegistry||0;
    var t=P.$DragOrientation;
    if(vt)t&1&&(t&=1);
    _.$DragRegistry|=t;
    return pt=t&~e
    }
    function b(){
    if(pt){
        _.$DragRegistry&=~P.$DragOrientation;
        pt=0
        }
    }
function w(){
    var e=$JssorUtils$.$CreateDivElement();
    $JssorUtils$.$SetStyles(e,J);
    $JssorUtils$.$SetStylePosition(e,"absolute");
    return e
    }
    function E(e){
    return(e%nt+nt)%nt
    }
    function S(e){
    return E(e)==Z
    }
    function x(e){
    return E(e)==Y
    }
    function T(e,t){
    O(_t?e:E(e),P.$SlideDuration,t)
    }
    function N(){
    $JssorUtils$.$Each(mt,function(e){
        e.$Show(e.$Options.$ChanceToShow>Ht)
        })
    }
    function C(t){
    t=$JssorUtils$.$GetEvent(t);
    var n=t.target?t.target:t.srcElement;
    var r=t.relatedTarget?t.relatedTarget:t.toElement;
    if(!$JssorUtils$.$IsChild(e,n)||$JssorUtils$.$IsChild(e,r)){
        return
    }
    Ht=1;
    N();
    Gt[Z].$TryActivate()
    }
    function k(){
    Ht=0;
    N()
    }
    function L(){
    J={
        $Width:rt,
        $Height:it,
        $Top:0,
        $Left:0
    };
    
    $JssorUtils$.$Each(K,function(e,t){
        $JssorUtils$.$SetStyles(e,J);
        $JssorUtils$.$SetStylePosition(e,"absolute");
        $JssorUtils$.$SetStyleOverflow(e,"hidden");
        $JssorUtils$.$HideElement(e)
        });
    $JssorUtils$.$SetStyles(z,J)
    }
    function A(e,t){
    O(e,t,true)
    }
    function O(e,t,n){
    if(Mt&&(!jt||P.$NaviQuitDrag)){
        Bt=true;
        jt=false;
        $t.$Stop();
        {
            if($JssorUtils$.$IsUndefined(t))t=Ct;
            var r=Wt.$GetPosition_Display();
            var i=e;
            if(n){
                i=r+e;
                if(e>0)i=Math.ceil(i);else i=Math.floor(i)
                    }
                    if(!_t){
                i=E(i);
                i=Math.max(0,Math.min(i,nt-ft))
                }
                var s=(i-r)%nt;
            i=r+s;
            var o=r==i?0:t*Math.abs(s);
            o=Math.min(o,t*ft*1.5);
            $t.$PlayCarousel(r,i,o)
            }
        }
}
var n=this;
n.$PlayTo=O;
n.$GoTo=function(e){
    O(e,0)
    };
    
n.$Next=function(){
    A(1)
    };
    
n.$Prev=function(){
    A(-1)
    };
    
n.$Pause=function(){
    St=false
    };
    
n.$Play=function(){
    if(!St){
        St=true;
        Gt[Z]&&Gt[Z].$TryActivate()
        }
    };

n.$SetSlideshowTransitions=function(e){
    $JssorDebug$.$Execute(function(){
        if(!e||!e.length){
            $JssorDebug$.$Error("Can not set slideshow transitions, no transitions specified.")
            }
        });
P.$SlideshowOptions.$Transitions=e
};

n.$SetCaptionTransitions=function(e){
    $JssorDebug$.$Execute(function(){
        if(!e||!e.length){
            $JssorDebug$.$Error("Can not set caption transitions, no transitions specified")
            }
        });
B.$CaptionTransitions=e;
B.$Version=$JssorUtils$.$GetNow()
};

n.$SlidesCount=function(){
    return K.length
    };
    
n.$CurrentIndex=function(){
    return Z
    };
    
n.$IsAutoPlaying=function(){
    return St
    };
    
n.$IsDragging=function(){
    return jt
    };
    
n.$IsSliding=function(){
    return Bt
    };
    
n.$IsMouseOver=function(){
    return!Ht
    };
    
n.$LastDragSucceded=function(){
    return Pt
    };
    
n.$GetOriginalWidth=function(){
    return $JssorUtils$.$GetStyleWidth(R||e)
    };
    
n.$GetOriginalHeight=function(){
    return $JssorUtils$.$GetStyleHeight(R||e)
    };
    
n.$GetScaleWidth=function(){
    return $JssorUtils$.$GetStyleWidth(e)
    };
    
n.$GetScaleHeight=function(){
    return $JssorUtils$.$GetStyleHeight(e)
    };
    
n.$SetScaleWidth=function(t){
    $JssorDebug$.$Execute(function(){
        if(!t||t<0){
            $JssorDebug$.$Fail("'$SetScaleWidth' error, 'width' should be positive value.")
            }
        });
if(!R){
    $JssorDebug$.$Execute(function(){
        var t=e.style.width;
        var n=e.style.height;
        var r=$JssorUtils$.$GetStyleWidth(e);
        var i=$JssorUtils$.$GetStyleHeight(e);
        if(!t){
            $JssorDebug$.$Fail("Cannot scale jssor slider, 'width' of 'outer container' not specified. Please specify 'width' in pixel.")
            }
            if(!n){
            $JssorDebug$.$Fail("Cannot scale jssor slider, 'height' of 'outer container' not specified. Please specify 'height' in pixel.")
            }
            if(t.indexOf("%")!=-1){
            $JssorDebug$.$Fail("Cannot scale jssor slider, 'width' of 'outer container' not valid. Please specify 'width' in pixel.")
            }
            if(n.indexOf("%")!=-1){
            $JssorDebug$.$Fail("Cannot scale jssor slider, 'height' of 'outer container' not valid. Please specify 'height' in pixel.")
            }
            if(!r){
            $JssorDebug$.$Fail("Cannot scale jssor slider, 'width' of 'outer container' not valid. 'width' of 'outer container' should be positive.")
            }
            if(!i){
            $JssorDebug$.$Fail("Cannot scale jssor slider, 'height' of 'outer container' not valid. 'height' of 'outer container' should be positive.")
            }
        });
var r=$JssorUtils$.$CloneNode(e,false);
$JssorUtils$.$RemoveAttribute(r,"id");
$JssorUtils$.$SetStylePosition(r,"relative");
$JssorUtils$.$SetStyleTop(r,0);
$JssorUtils$.$SetStyleLeft(r,0);
$JssorUtils$.$SetStyleOverflow(r,"visible");
R=$JssorUtils$.$CloneNode(e,false);
$JssorUtils$.$RemoveAttribute(R,"id");
$JssorUtils$.$SetStyleCssText(R,"");
$JssorUtils$.$SetStylePosition(R,"absolute");
$JssorUtils$.$SetStyleTop(R,0);
$JssorUtils$.$SetStyleLeft(R,0);
$JssorUtils$.$SetStyleWidth(R,$JssorUtils$.$GetStyleWidth(e));
$JssorUtils$.$SetStyleHeight(R,$JssorUtils$.$GetStyleHeight(e));
$JssorUtils$.$SetStyleTransformOrigin(R,"0 0");
$JssorUtils$.$AppendChild(R,r);
var i=$JssorUtils$.$GetChildren(e);
$JssorUtils$.$AppendChild(e,R);
$JssorUtils$.$AppendChildren(r,i);
$JssorUtils$.$ShowElement(r);
$JssorUtils$.$ShowElement(R)
}
$JssorDebug$.$Execute(function(){
    if(!M){
        M=n.$Elmt.scrollWidth
        }
    });
Kt=t/$JssorUtils$.$GetStyleWidth(R);
$JssorUtils$.$SetStyleScale(R,Kt);
$JssorUtils$.$SetStyleWidth(e,t);
$JssorUtils$.$SetStyleHeight(e,Kt*$JssorUtils$.$GetStyleHeight(R))
};

n.$GetVirtualIndex=function(e){
    var t=Math.ceil(E(Ot/at));
    var n=E(e-Z+t);
    if(n>ft){
        if(e-Z>nt/2)e-=nt;
        else if(e-Z<=-nt/2)e+=nt
            }else{
        e=Z+n-t
        }
        return e
    };
    
$JssorEventManager$.call(this);
n.$Elmt=e=$JssorUtils$.$GetElement(e);
var M;
var D=1;
$JssorDebug$.$Execute(function(){
    var t=$JssorUtils$.$GetElement(e);
    if(!t)$JssorDebug$.$Fail("Outer container '"+e+"' not found.")
        });
var P=$JssorUtils$.$Extend({
    $FillMode:0,
    $LazyLoading:1,
    $StartIndex:0,
    $AutoPlay:false,
    $Loop:true,
    $HWA:true,
    $NaviQuitDrag:true,
    $AutoPlaySteps:1,
    $AutoPlayInterval:3e3,
    $PauseOnHover:3,
    $HwaMode:1,
    $SlideDuration:500,
    $SlideEasing:$JssorEasing$.$EaseOutQuad,
    $MinDragOffsetToSlide:20,
    $SlideSpacing:0,
    $DisplayPieces:1,
    $ParkingPosition:0,
    $UISearchMode:1,
    $PlayOrientation:1,
    $DragOrientation:1
},t);
$JssorDebug$.$Execute(function(){
    P=$JssorUtils$.$Extend({
        $ArrowKeyNavigation:undefined,
        $SlideWidth:undefined,
        $SlideHeight:undefined,
        $SlideshowOptions:undefined,
        $CaptionSliderOptions:undefined,
        $BulletNavigatorOptions:undefined,
        $ArrowNavigatorOptions:undefined,
        $ThumbnailNavigatorOptions:undefined
    },P)
    });
var H=P.$SlideshowOptions;
var B=$JssorUtils$.$Extend({
    $Class:$JssorCaptionSliderBase$,
    $PlayInMode:1,
    $PlayOutMode:1
},P.$CaptionSliderOptions);
var j=P.$BulletNavigatorOptions;
var F=P.$ArrowNavigatorOptions;
var I=P.$ThumbnailNavigatorOptions;
$JssorDebug$.$Execute(function(){
    if(H&&!H.$Class){
        $JssorDebug$.$Fail("Option $SlideshowOptions error, class not specified.")
        }
    });
$JssorDebug$.$Execute(function(){
    if(P.$CaptionSliderOptions&&!P.$CaptionSliderOptions.$Class){
        $JssorDebug$.$Fail("Option $CaptionSliderOptions error, class not specified.")
        }
    });
$JssorDebug$.$Execute(function(){
    if(j&&!j.$Class){
        $JssorDebug$.$Fail("Option $BulletNavigatorOptions error, class not specified.")
        }
    });
$JssorDebug$.$Execute(function(){
    if(F&&!F.$Class){
        $JssorDebug$.$Fail("Option $ArrowNavigatorOptions error, class not specified.")
        }
    });
$JssorDebug$.$Execute(function(){
    if(I&&!I.$Class){
        $JssorDebug$.$Fail("Option $ArrowNavigatorOptions error, class not specified.")
        }
    });
var q=P.$UISearchMode;
var R;
var U=$JssorUtils$.$FindFirstChildByAttribute(e,"slides",null,q);
var z=$JssorUtils$.$FindFirstChildByAttribute(e,"loading",null,q)||$JssorUtils$.$CreateDivElement(document);
var W=$JssorUtils$.$FindFirstChildByAttribute(e,"navigator",null,q);
var X=$JssorUtils$.$FindFirstChildByAttribute(e,"thumbnavigator",null,q);
var V=$JssorUtils$.$GetStyleWidth(U);
var $=$JssorUtils$.$GetStyleHeight(U);
$JssorDebug$.$Execute(function(){
    if(isNaN(V))$JssorDebug$.$Fail("Width of slides container wrong specification, it should be specified by inline style in pixels (like style='width: 600px;').");
    if($JssorUtils$.$IsUndefined(V))$JssorDebug$.$Fail("Width of slides container not specified, it should be specified by inline style in pixels (like style='width: 600px;').");
    if(isNaN($))$JssorDebug$.$Fail("Height of slides container wrong specification, it should be specified by inline style in pixels (like style='height: 300px;').");
    if($JssorUtils$.$IsUndefined($))$JssorDebug$.$Fail("Height of slides container not specified, it should be specified by inline style in pixels (like style='height: 300px;').");
    var e=$JssorUtils$.$GetStyleOverflow(U);
    var t=$JssorUtils$.$GetStyleOverflowX(U);
    var n=$JssorUtils$.$GetStyleOverflowY(U);
    if(e!="hidden"&&(t!="hidden"||n!="hidden"))$JssorDebug$.$Fail("Overflow of slides container wrong specification, it should be specified as 'hidden' (style='overflow:hidden;').")
        });
$JssorDebug$.$Execute(function(){
    if(!$JssorUtils$.$IsNumeric(P.$DisplayPieces))$JssorDebug$.$Fail("Option $DisplayPieces error, it should be a numeric value and greater than or equal to 1.");
    if(P.$DisplayPieces<1)$JssorDebug$.$Fail("Option $DisplayPieces error, it should be greater than or equal to 1.");
    if(P.$DisplayPieces>1&&P.$DragOrientation&&P.$DragOrientation!=P.$PlayOrientation)$JssorDebug$.$Fail("Option $DragOrientation error, it should be 0 or the same of $PlayOrientation when $DisplayPieces is greater than 1.");
    if(!$JssorUtils$.$IsNumeric(P.$ParkingPosition))$JssorDebug$.$Fail("Option $ParkingPosition error, it should be a numeric value.");
    if(P.$ParkingPosition&&P.$DragOrientation&&P.$DragOrientation!=P.$PlayOrientation)$JssorDebug$.$Fail("Option $DragOrientation error, it should be 0 or the same of $PlayOrientation when $ParkingPosition is not equal to 0.")
        });
var J;
var K=$JssorUtils$.$GetChildren(U);
$JssorDebug$.$Execute(function(){
    if(K.length<1){
        $JssorDebug$.$Error("Slides html code definition error, there must be at least 1 slide to initialize a slider.")
        }
    });
var Q=0;
var G=0;
var Y;
var Z=-1;
var et;
var tt;
var nt=K.length;
var rt=P.$SlideWidth||V;
var it=P.$SlideHeight||$;
var st=P.$SlideSpacing;
var ot=rt+st;
var ut=it+st;
var at=P.$PlayOrientation==1?ot:ut;
var ft=Math.min(P.$DisplayPieces,nt);
var lt;
var ct=0;
var ht;
var pt;
var dt;
var vt;
var mt=[];
var gt;
var yt;
var bt;
var wt;
var Et;
var St;
var xt=P.$AutoPlaySteps;
var Tt=P.$PauseOnHover;
var Nt=P.$AutoPlayInterval;
var Ct=P.$SlideDuration;
var kt;
var Lt;
var At;
var Ot;
var Mt=ft<nt;
var _t=P.$Loop&&Mt;
if(!_t)P.$ParkingPosition=0;
if(P.$DisplayPieces>1||P.$ParkingPosition)P.$DragOrientation&=P.$PlayOrientation;
var Dt=P.$DragOrientation;
var Pt;
var Ht=1;
var Bt;
var jt;
var Ft;
var It=0;
var qt=0;
var Rt;
var Ut;
var zt;
var Wt;
var Xt;
var Vt;
var $t;
var Jt=new u;
var Kt;
{
    St=P.$AutoPlay;
    n.$Options=t;
    L();
    e["jssor-slider"]=true;
    $JssorUtils$.$SetStyleZIndex(U,$JssorUtils$.$GetStyleZIndex(U));
    $JssorUtils$.$SetStylePosition(U,"absolute");
    lt=$JssorUtils$.$CloneNode(U);
    $JssorUtils$.$InsertBefore($JssorUtils$.$GetParentNode(U),lt,U);
    if(H){
        wt=H.$ShowLink;
        kt=H.$Class;
        $JssorDebug$.$Execute(function(){
            if(!H.$Transitions||!H.$Transitions.length){
                $JssorDebug$.$Error("Invalid '$SlideshowOptions', no '$Transitions' specified.")
                }
            });
    At=ft==1&&nt>1&&kt&&(!$JssorUtils$.$IsBrowserIE()||$JssorUtils$.$GetBrowserVersion()>=8)
    }
    Ot=At||ft>=nt?0:P.$ParkingPosition;
var Qt=U;
var Gt=[];
var Yt;
var Zt;
var en="mousedown";
var tn="mousemove";
var nn="mouseup";
var rn;
var sn;
var on;
var un;
var an;
var fn;
{
    if(window.navigator.msPointerEnabled){
        en="MSPointerDown";
        tn="MSPointerMove";
        nn="MSPointerUp";
        rn="MSPointerCancel";
        if(P.$DragOrientation){
            var ln="none";
            if(P.$DragOrientation==1){
                ln="pan-y"
                }else if(P.$DragOrientation==2){
                ln="pan-x"
                }
                $JssorUtils$.$SetAttribute(Qt.style,"-ms-touch-action",ln)
            }
        }else if("ontouchstart"in window||"createTouch"in document){
    vt=true;
    en="touchstart";
    tn="touchmove";
    nn="touchend";
    rn="touchcancel"
    }
    Vt=new s;
if(At)Yt=new kt(Jt,rt,it,H,vt);
$JssorUtils$.$AppendChild(lt,Vt.$Wrapper);
$JssorUtils$.$SetStyleOverflow(U,"hidden");
{
    Zt=w();
    $JssorUtils$.$SetStyleBackgroundColor(Zt,"#000");
    $JssorUtils$.$SetStyleOpacity(Zt,0);
    $JssorUtils$.$InsertBefore(Qt,Zt,Qt.firstChild)
    }
    for(var cn=0;cn<K.length;cn++){
    var hn=K[cn];
    var pn=new a(hn,cn);
    Gt.push(pn)
    }
    $JssorUtils$.$HideElement(z);
$JssorDebug$.$Execute(function(){
    $JssorUtils$.$SetAttribute(z,"debug-id","loading-container")
    });
Wt=new i;
$t=new o(Wt,Vt);
$JssorDebug$.$Execute(function(){
    $JssorUtils$.$SetAttribute(Qt,"debug-id","slide-board")
    });
if(Dt){
    $JssorUtils$.$AddEvent(U,en,c);
    $JssorUtils$.$AddEvent(document,nn,d);
    rn&&$JssorUtils$.$AddEvent(document,rn,d)
    }
}
Tt&=vt?2:1;
if(W&&j){
    gt=new j.$Class(W,j);
    mt.push(gt)
    }
    if(F){
    yt=new F.$Class(e,F,P.$UISearchMode);
    mt.push(yt)
    }
    if(X&&I){
    I.$StartIndex=P.$StartIndex;
    bt=new I.$Class(X,I);
    mt.push(bt)
    }
    $JssorUtils$.$Each(mt,function(e){
    e.$Reset(nt,Gt,z);
    e.$On($JssorNavigatorEvents$.$NAVIGATIONREQUEST,T)
    });
$JssorUtils$.$AddEvent(e,"mouseout",C);
$JssorUtils$.$AddEvent(e,"mouseover",k);
N();
if(P.$ArrowKeyNavigation){
    $JssorUtils$.$AddEvent(document,"keydown",function(e){
        if(e.keyCode==$JssorKeyCode$.$LEFT){
            A(-1)
            }else if(e.keyCode==$JssorKeyCode$.$RIGHT){
            A(1)
            }
        })
}
n.$SetScaleWidth(n.$GetOriginalWidth());
$t.$PlayCarousel(P.$StartIndex,P.$StartIndex,0)
}
}
var e=0;
var t=1;
var n=2;
var r=3;
var i=3;
var s=12;
var o=1;
var u=2;
var a=4;
var f=8;
var l=256;
var c=512;
var h=1024;
var p=2048;
var d=p+o;
var v=p+u;
var m=c+o;
var g=c+u;
var y=l+a;
var b=l+f;
var w=h+a;
var E=h+f;
$JssorSlideshowFormations$.$FormationStraight=function(e){
    var t=e.$Cols;
    var n=e.$Rows;
    var r=e.$Assembly;
    var i=e.$Count;
    var s=[];
    var o=0;
    var u=0;
    var a=0;
    var f=t-1;
    var l=n-1;
    var c=i-1;
    var h;
    var p;
    for(a=0;a<n;a++){
        for(u=0;u<t;u++){
            h=a+","+u;
            switch(r){
                case d:
                    p=c-(u*n+(l-a));
                    break;
                case w:
                    p=c-(a*t+(f-u));
                    break;
                case m:
                    p=c-(u*n+a);
                case y:
                    p=c-(a*t+u);
                    break;
                case v:
                    p=u*n+a;
                    break;
                case b:
                    p=a*t+(f-u);
                    break;
                case g:
                    p=u*n+(l-a);
                    break;
                default:
                    p=a*t+u;
                    break
                    }
                    C(s,p,[a,u])
            }
        }
        return s
};

$JssorSlideshowFormations$.$FormationSwirl=function(i){
    var s=i.$Cols;
    var o=i.$Rows;
    var u=i.$Assembly;
    var a=i.$Count;
    var f=[];
    var l=[];
    var c=0;
    var h=0;
    var p=0;
    var E=s-1;
    var S=o-1;
    var x=a-1;
    var T;
    var N;
    var k=0;
    switch(u){
        case d:
            h=E;
            p=0;
            N=[n,t,r,e];
            break;
        case w:
            h=0;
            p=S;
            N=[e,r,t,n];
            break;
        case m:
            h=E;
            p=S;
            N=[r,t,n,e];
            break;
        case y:
            h=E;
            p=S;
            N=[t,r,e,n];
            break;
        case v:
            h=0;
            p=0;
            N=[n,e,r,t];
            break;
        case b:
            h=E;
            p=0;
            N=[t,n,e,r];
            break;
        case g:
            h=0;
            p=S;
            N=[r,e,n,t];
            break;
        default:
            h=0;
            p=0;
            N=[e,n,t,r];
            break
            }
            c=0;
    while(c<a){
        T=p+","+h;
        if(h>=0&&h<s&&p>=0&&p<o&&!l[T]){
            l[T]=true;
            C(f,c++,[p,h])
            }else{
            switch(N[k++%N.length]){
                case e:
                    h--;
                    break;
                case n:
                    p--;
                    break;
                case t:
                    h++;
                    break;
                case r:
                    p++;
                    break
                    }
                }
        switch(N[k%N.length]){
        case e:
            h++;
            break;
        case n:
            p++;
            break;
        case t:
            h--;
            break;
        case r:
            p--;
            break
            }
        }
return f
};

$JssorSlideshowFormations$.$FormationZigZag=function(i){
    var s=i.$Cols;
    var o=i.$Rows;
    var u=i.$Assembly;
    var a=i.$Count;
    var f=[];
    var l=0;
    var c=0;
    var h=0;
    var p=s-1;
    var E=o-1;
    var S=a-1;
    var x;
    var T;
    var N=0;
    switch(u){
        case d:
            c=p;
            h=0;
            T=[n,t,r,t];
            break;
        case w:
            c=0;
            h=E;
            T=[e,r,t,r];
            break;
        case m:
            c=p;
            h=E;
            T=[r,t,n,t];
            break;
        case y:
            c=p;
            h=E;
            T=[t,r,e,r];
            break;
        case v:
            c=0;
            h=0;
            T=[n,e,r,e];
            break;
        case b:
            c=p;
            h=0;
            T=[t,n,e,n];
            break;
        case g:
            c=0;
            h=E;
            T=[r,e,n,e];
            break;
        default:
            c=0;
            h=0;
            T=[e,n,t,n];
            break
            }
            l=0;
    while(l<a){
        x=h+","+c;
        if(c>=0&&c<s&&h>=0&&h<o&&typeof f[x]=="undefined"){
            C(f,l++,[h,c]);
            switch(T[N%T.length]){
                case e:
                    c++;
                    break;
                case n:
                    h++;
                    break;
                case t:
                    c--;
                    break;
                case r:
                    h--;
                    break
                    }
                }else{
        switch(T[N++%T.length]){
            case e:
                c--;
                break;
            case n:
                h--;
                break;
            case t:
                c++;
                break;
            case r:
                h++;
                break
                }
                switch(T[N++%T.length]){
            case e:
                c++;
                break;
            case n:
                h++;
                break;
            case t:
                c--;
                break;
            case r:
                h--;
                break
                }
            }
}
return f
};

$JssorSlideshowFormations$.$FormationStraightStairs=function(e){
    var t=e.$Cols;
    var n=e.$Rows;
    var r=e.$Assembly;
    var i=e.$Count;
    var s=[];
    var o=0;
    var u=0;
    var a=0;
    var f=t-1;
    var l=n-1;
    var c=i-1;
    var h;
    switch(r){
        case d:case g:case m:case v:
            var p=0;
            var S=0;
            break;
        case b:case w:case y:case E:
            var p=f;
            var S=0;
            break;
        default:
            r=E;
            var p=f;
            var S=0;
            break
            }
            u=p;
    a=S;
    while(o<i){
        h=a+","+u;
        if(T(r)||x(r)){
            C(s,c-o++,[a,u])
            }else{
            C(s,o++,[a,u])
            }
            switch(r){
            case d:case g:
                u--;
                a++;
                break;
            case m:case v:
                u++;
                a--;
                break;
            case b:case w:
                u--;
                a--;
                break;
            case E:case y:default:
                u++;
                a++;
                break
                }
                if(u<0||a<0||u>f||a>l){
            switch(r){
                case d:case g:
                    p++;
                    break;
                case b:case w:case m:case v:
                    S++;
                    break;
                case E:case y:default:
                    p--;
                    break
                    }
                    if(p<0||S<0||p>f||S>l){
                switch(r){
                    case d:case g:
                        p=f;
                        S++;
                        break;
                    case m:case v:
                        S=l;
                        p++;
                        break;
                    case b:case w:
                        S=l;
                        p--;
                        break;
                    case E:case y:default:
                        p=0;
                        S++;
                        break
                        }
                        if(S>l)S=l;
                else if(S<0)S=0;
                else if(p>f)p=f;
                else if(p<0)p=0
                    }
                    a=S;
            u=p
            }
        }
    return s
};

$JssorSlideshowFormations$.$FormationSquare=function(e){
    var t=e.$Cols||1;
    var n=e.$Rows||1;
    var r=[];
    var i=0;
    var s;
    var o;
    var u;
    var a;
    var f;
    u=t<n?(n-t)/2:0;
    a=t>n?(t-n)/2:0;
    f=Math.round(Math.max(t/2,n/2))+1;
    for(s=0;s<t;s++){
        for(o=0;o<n;o++)C(r,f-Math.min(s+1+u,o+1+a,t-s+u,n-o+a),[o,s])
            }
            return r
    };
    
$JssorSlideshowFormations$.$FormationRectangle=function(e){
    var t=e.$Cols||1;
    var n=e.$Rows||1;
    var r=[];
    var i=0;
    var s;
    var o;
    var u;
    u=Math.round(Math.min(t/2,n/2))+1;
    for(s=0;s<t;s++){
        for(o=0;o<n;o++)C(r,u-Math.min(s+1,o+1,t-s,n-o),[o,s])
            }
            return r
    };
    
$JssorSlideshowFormations$.$FormationRandom=function(e){
    var t=[];
    var n,r,i;
    for(n=0;n<e.$Rows;n++){
        for(r=0;r<e.$Cols;r++)C(t,Math.ceil(1e5*Math.random())%13,[n,r])
            }
            return t
    };
    
$JssorSlideshowFormations$.$FormationCircle=function(e){
    var t=e.$Cols||1;
    var n=e.$Rows||1;
    var r=[];
    var i=0;
    var s;
    var o;
    var u=t/2-.5;
    var a=n/2-.5;
    for(s=0;s<t;s++){
        for(o=0;o<n;o++)C(r,Math.round(Math.sqrt(Math.pow(s-u,2)+Math.pow(o-a,2))),[o,s])
            }
            return r
    };
    
$JssorSlideshowFormations$.$FormationCross=function(e){
    var t=e.$Cols||1;
    var n=e.$Rows||1;
    var r=[];
    var i=0;
    var s;
    var o;
    var u=t/2-.5;
    var a=n/2-.5;
    for(s=0;s<t;s++){
        for(o=0;o<n;o++)C(r,Math.round(Math.min(Math.abs(s-u),Math.abs(o-a))),[o,s])
            }
            return r
    };
    
$JssorSlideshowFormations$.$FormationRectangleCross=function(e){
    var t=e.$Cols||1;
    var n=e.$Rows||1;
    var r=[];
    var i=0;
    var s;
    var o;
    var u=t/2-.5;
    var a=n/2-.5;
    var f=Math.max(u,a)+1;
    for(s=0;s<t;s++){
        for(o=0;o<n;o++)C(r,Math.round(f-Math.max(u-Math.abs(s-u),a-Math.abs(o-a)))-1,[o,s])
            }
            return r
    };
    
var M=1;
$JssorSlideshowRunner$=window.$JssorSlideshowRunner$=function(e,t,n,r,i){
    function m(){
        var e=this;
        var t=0;
        $JssorAnimator$.call(e,0,o);
        e.$OnPositionChange=function(e,n){
            if(n-t>v){
                t=n;
                f&&f.$ShowFrame(n);
                a&&a.$ShowFrame(n)
                }
            };
        
    e.$Transition=d
    }
    var s=this;
var o;
var u;
var a;
var f;
var l;
var c;
var h=0;
var p=r.$TransitionsOrder;
var d;
var v=16;
s.$GetTransition=function(e){
    var t=0;
    var n=r.$Transitions;
    var i=n.length;
    if(p){
        if(i>e&&($JssorUtils$.$IsBrowserChrome()||$JssorUtils$.$IsBrowserSafari()||$JssorUtils$.$IsBrowserFireFox())){
            i-=i%e
            }
            t=h++%i
        }else{
        t=Math.floor(Math.random()*i)
        }
        n[t]&&(n[t].$Index=t);
    return n[t]
    };
    
s.$Initialize=function(r,i,u,h,p){
    $JssorDebug$.$Execute(function(){
        if(a){
            $JssorDebug$.$Fail("slideshow runner has not been cleared.")
            }
        });
d=p;
p=L(p,v);
c=u;
l=h;
var m=h.$Item;
var g=u.$Item;
m["no-image"]=!h.$Image;
g["no-image"]=!u.$Image;
var y=m;
var b=g;
var w=p;
var E=p.$Brother||L({},v);
if(!p.$SlideOut){
    y=g;
    b=m
    }
    var S=E.$Shift||0;
a=new O(e,b,E,Math.max(S-E.$Interval,0),t,n);
f=new O(e,y,w,Math.max(E.$Interval-S,0),t,n);
a.$ShowFrame(0);
f.$ShowFrame(0);
o=Math.max(a.$EndTime,f.$EndTime);
s.$Index=r
};

s.$Clear=function(){
    e.$Clear();
    a=null;
    f=null
    };
    
s.$GetProcessor=function(){
    var e=null;
    if(f)e=new m;
    return e
    };
    
{
    if($JssorUtils$.$IsBrowserIe9Earlier()||$JssorUtils$.$IsBrowserOpera()||i&&$JssorUtils$.$GetWebKitVersion<537){
        v=32
        }
        $JssorEventManager$.call(s);
    $JssorAnimator$.call(s,-1e7,1e7);
    $JssorDebug$.$LiveStamp(s,"slideshow_runner_"+M++)
    }
};

_.$EVT_CLICK=21;
_.$EVT_DRAG_START=22;
_.$EVT_DRAG_END=23;
_.$EVT_SWIPE_START=24;
_.$EVT_SWIPE_END=25;
_.$EVT_LOAD_START=26;
_.$EVT_LOAD_END=27;
_.$EVT_POSITION_CHANGE=202;
_.$EVT_PARK=203;
_.$EVT_SLIDESHOW_START=206;
_.$EVT_SLIDESHOW_END=207;
_.$EVT_PROGRESS_CHANGE=208;
_.$EVT_STATE_CHANGE=209;
_.$EVT_ROLLBACK_START=210;
_.$EVT_ROLLBACK_END=211;
window.$JssorSlider$=$JssorSlider$=_
};

var $JssorNavigatorEvents$={
    $NAVIGATIONREQUEST:1,
    $INDEXCHANGE:2,
    $RESET:3
};

var $JssorBulletNavigator$=window.$JssorBulletNavigator$=function(e,t){
    function w(e){
        if(e!=-1)b[e].$Activate(e==a)
            }
            function E(e){
        n.$TriggerEvent($JssorNavigatorEvents$.$NAVIGATIONREQUEST,e*l)
        }
        var n=this;
    $JssorEventManager$.call(n);
    e=$JssorUtils$.$GetElement(e);
    var r;
    var i;
    var s;
    var o;
    var u;
    var a=0;
    var f;
    var l;
    var c;
    var h;
    var p;
    var d;
    var v;
    var m;
    var g;
    var y=[];
    var b=[];
    n.$Elmt=e;
    n.$GetCurrentIndex=function(){
        return u
        };
        
    n.$SetCurrentIndex=function(e){
        if(e!=u){
            var t=a;
            var n=Math.floor(e/l);
            a=n;
            u=e;
            w(t);
            w(n)
            }
        };
    
n.$Show=function(t){
    $JssorUtils$.$ShowElement(e,t)
    };
    
var S;
n.$Reset=function(t){
    if(!S){
        i=t;
        r=Math.ceil(t/l);
        a=0;
        var n=m+h;
        var u=g+p;
        var w=Math.ceil(r/c)-1;
        s=m+n*(!d?w:c-1);
        o=g+u*(d?w:c-1);
        $JssorUtils$.$SetStyleWidth(e,s);
        $JssorUtils$.$SetStyleHeight(e,o);
        if(f.$AutoCenter&1){
            $JssorUtils$.$SetStyleLeft(e,($JssorUtils$.$GetStyleWidth($JssorUtils$.$GetParentNode(e))-s)/2)
            }
            if(f.$AutoCenter&2){
            $JssorUtils$.$SetStyleTop(e,($JssorUtils$.$GetStyleHeight($JssorUtils$.$GetParentNode(e))-o)/2)
            }
            for(var x=0;x<r;x++){
            var T=$JssorUtils$.$CreateSpanElement();
            $JssorUtils$.$SetInnerText(T,x+1);
            var N=$JssorUtils$.$BuildElement(v,"NumberTemplate",T,true);
            $JssorUtils$.$SetStylePosition(N,"absolute");
            var C=x%(w+1);
            $JssorUtils$.$SetStyleLeft(N,!d?n*C:x%c*n);
            $JssorUtils$.$SetStyleTop(N,d?u*C:Math.floor(x/(w+1))*u);
            $JssorUtils$.$AppendChild(e,N);
            y[x]=N;
            if(f.$ActionMode&1)$JssorUtils$.$AddEvent(N,"click",$JssorUtils$.$CreateCallback(null,E,x));
            if(f.$ActionMode&2)$JssorUtils$.$AddEvent(N,"mouseover",$JssorUtils$.$CreateCallback(null,E,x));
            b[x]=$JssorUtils$.$Buttonize(N)
            }
            S=true
        }
    };

{
    n.$Options=f=$JssorUtils$.$Extend({
        $SpacingX:0,
        $SpacingY:0,
        $Orientation:1,
        $ActionMode:1
    },t);
    $JssorDebug$.$Execute(function(){
        f=$JssorUtils$.$Extend({
            $Steps:undefined,
            $Lanes:undefined
        },f)
        });
    v=$JssorUtils$.$FindFirstChildByAttribute(e,"prototype");
    $JssorDebug$.$Execute(function(){
        if(!v)$JssorDebug$.$Fail("Navigator item prototype not defined.");
        if(isNaN($JssorUtils$.$GetStyleWidth(v))){
            $JssorDebug$.$Fail("Width of 'navigator item prototype' not specified.")
            }
            if(isNaN($JssorUtils$.$GetStyleHeight(v))){
            $JssorDebug$.$Fail("Height of 'navigator item prototype' not specified.")
            }
        });
m=$JssorUtils$.$GetStyleWidth(v);
g=$JssorUtils$.$GetStyleHeight(v);
$JssorUtils$.$RemoveChild(e,v);
l=f.$Steps||1;
c=f.$Lanes||1;
h=f.$SpacingX;
p=f.$SpacingY;
d=f.$Orientation-1
}
};

var $JssorArrowNavigator$=window.$JssorArrowNavigator$=function(e,t,n){
    function d(e){
        r.$TriggerEvent($JssorNavigatorEvents$.$NAVIGATIONREQUEST,e,true)
        }
        var r=this;
    $JssorEventManager$.call(r);
    $JssorDebug$.$Execute(function(){
        var t=$JssorUtils$.$FindFirstChildByAttribute(e,"arrowleft",null,n);
        var r=$JssorUtils$.$FindFirstChildByAttribute(e,"arrowright",null,n);
        if(!t)$JssorDebug$.$Fail("Option '$ArrowNavigatorOptions' spepcified, but UI 'arrowleft' not defined. Define 'arrowleft' to enable direct navigation, or remove option '$ArrowNavigatorOptions' to disable direct navigation.");
        if(!r)$JssorDebug$.$Fail("Option '$ArrowNavigatorOptions' spepcified, but UI 'arrowright' not defined. Define 'arrowright' to enable direct navigation, or remove option '$ArrowNavigatorOptions' to disable direct navigation.");
        if(isNaN($JssorUtils$.$GetStyleWidth(t))){
            $JssorDebug$.$Fail("Width of 'arrow left' not specified.")
            }
            if(isNaN($JssorUtils$.$GetStyleWidth(r))){
            $JssorDebug$.$Fail("Width of 'arrow right' not specified.")
            }
            if(isNaN($JssorUtils$.$GetStyleHeight(t))){
            $JssorDebug$.$Fail("Height of 'arrow left' not specified.")
            }
            if(isNaN($JssorUtils$.$GetStyleHeight(r))){
            $JssorDebug$.$Fail("Height of 'arrow right' not specified.")
            }
        });
var i=$JssorUtils$.$FindFirstChildByAttribute(e,"arrowleft",null,n);
var s=$JssorUtils$.$FindFirstChildByAttribute(e,"arrowright",null,n);
var o;
var u;
var a;
var f;
var l=$JssorUtils$.$GetStyleWidth(e);
var c=$JssorUtils$.$GetStyleHeight(e);
var h=$JssorUtils$.$GetStyleWidth(i);
var p=$JssorUtils$.$GetStyleHeight(i);
r.$GetCurrentIndex=function(){
    return u
    };
    
r.$SetCurrentIndex=function(e,t,n){
    if(n){
        u=t
        }else{
        u=e
        }
    };

r.$Show=function(e){
    $JssorUtils$.$ShowElement(i,e);
    $JssorUtils$.$ShowElement(s,e)
    };
    
var v;
r.$Reset=function(e){
    o=e;
    u=0;
    if(!v){
        if(a.$AutoCenter&1){
            $JssorUtils$.$SetStyleLeft(i,(l-h)/2);
            $JssorUtils$.$SetStyleLeft(s,(l-h)/2)
            }
            if(a.$AutoCenter&2){
            $JssorUtils$.$SetStyleTop(i,(c-p)/2);
            $JssorUtils$.$SetStyleTop(s,(c-p)/2)
            }
            $JssorUtils$.$AddEvent(i,"click",$JssorUtils$.$CreateCallback(null,d,-f));
        $JssorUtils$.$AddEvent(s,"click",$JssorUtils$.$CreateCallback(null,d,f));
        $JssorUtils$.$Buttonize(i);
        $JssorUtils$.$Buttonize(s)
        }
    };

{
    r.$Options=a=$JssorUtils$.$Extend({
        $Steps:1
    },t);
    f=a.$Steps
    }
};

var $JssorThumbnailNavigator$=window.$JssorThumbnailNavigator$=function(e,t){
    function E(e,t){
        function f(e){
            u.$Activate(s==t)
            }
            function l(e){
            if(!g.$LastDragSucceded()){
                var r=(c-t%c)%c;
                var i=g.$GetVirtualIndex((t+r)/c);
                var s=i*c-r;
                n.$TriggerEvent($JssorNavigatorEvents$.$NAVIGATIONREQUEST,s)
                }
            }
        var r=this;
    var i;
    var u;
    var a;
    $JssorDebug$.$Execute(function(){
        r.$Wrapper=undefined
        });
    r.$Index=t;
    r.$Highlight=f;
    {
        a=e.$Thumb||e.$Image||$JssorUtils$.$CreateDivElement();
        r.$Wrapper=i=$JssorUtils$.$BuildElement(w,"ThumbnailTemplate",a,true);
        u=$JssorUtils$.$Buttonize(i);
        if(o.$ActionMode&1)$JssorUtils$.$AddEvent(i,"click",l);
        if(o.$ActionMode&2)$JssorUtils$.$AddEvent(i,"mouseover",l)
            }
        }
var n=this;
var r;
var i;
var s;
var o;
var u=[];
var a;
var f;
var l;
var c;
var h;
var p;
var d;
var v;
var m;
var g;
var y=-1;
var b;
var w;
$JssorEventManager$.call(n);
e=$JssorUtils$.$GetElement(e);
n.$GetCurrentIndex=function(){
    return s
    };
    
n.$SetCurrentIndex=function(e,t,n){
    var r=s;
    s=e;
    if(r!=-1)u[r].$Highlight();
    u[e].$Highlight();
    if(!n){
        g.$PlayTo(g.$GetVirtualIndex(Math.floor(t/c)))
        }
    };

n.$Show=function(t){
    $JssorUtils$.$ShowElement(e,t)
    };
    
var S;
n.$Reset=function(t,n,a){
    if(!S){
        r=t;
        i=Math.ceil(r/c);
        s=-1;
        m=Math.min(m,n.length);
        var y=o.$Orientation&1;
        var w=d+(d+h)*(c-1)*(1-y);
        var x=v+(v+p)*(c-1)*y;
        var T=w+(w+h)*(m-1)*y;
        var N=x+(x+p)*(m-1)*(1-y);
        $JssorUtils$.$SetStylePosition(b,"absolute");
        $JssorUtils$.$SetStyleOverflow(b,"hidden");
        if(o.$AutoCenter&1){
            $JssorUtils$.$SetStyleLeft(b,(f-T)/2)
            }
            if(o.$AutoCenter&2){
            $JssorUtils$.$SetStyleTop(b,(l-N)/2)
            }
            $JssorUtils$.$SetStyleWidth(b,T);
        $JssorUtils$.$SetStyleHeight(b,N);
        var C=[];
        $JssorUtils$.$Each(n,function(e,t){
            var n=new E(e,t);
            var r=n.$Wrapper;
            var i=Math.floor(t/c);
            var s=t%c;
            $JssorUtils$.$SetStyleLeft(r,(d+h)*s*(1-y));
            $JssorUtils$.$SetStyleTop(r,(v+p)*s*y);
            if(!C[i]){
                C[i]=$JssorUtils$.$CreateDivElement();
                $JssorUtils$.$AppendChild(b,C[i])
                }
                $JssorUtils$.$AppendChild(C[i],r);
            u.push(n)
            });
        var k=$JssorUtils$.$Extend({
            $AutoPlay:false,
            $NaviQuitDrag:false,
            $SlideWidth:w,
            $SlideHeight:x,
            $SlideSpacing:h*y+p*(1-y),
            $MinDragOffsetToSlide:12,
            $SlideDuration:200,
            $PauseOnHover:3,
            $PlayOrientation:o.$Orientation,
            $DragOrientation:o.$DisableDrag?0:o.$Orientation
            },o);
        g=new $JssorSlider$(e,k);
        S=true
        }
    };

{
    n.$Options=o=$JssorUtils$.$Extend({
        $SpacingX:3,
        $SpacingY:3,
        $DisplayPieces:1,
        $Orientation:1,
        $AutoCenter:3,
        $ActionMode:1
    },t);
    $JssorDebug$.$Execute(function(){
        o=$JssorUtils$.$Extend({
            $Lanes:undefined,
            $Width:undefined,
            $Height:undefined
        },o)
        });
    f=$JssorUtils$.$GetStyleWidth(e);
    l=$JssorUtils$.$GetStyleHeight(e);
    $JssorDebug$.$Execute(function(){
        if(!f)$JssorDebug$.$Fail("width of 'thumbnavigator' container not specified.");
        if(!l)$JssorDebug$.$Fail("height of 'thumbnavigator' container not specified.")
            });
    b=$JssorUtils$.$FindFirstChildByAttribute(e,"slides");
    w=$JssorUtils$.$FindFirstChildByAttribute(b,"prototype");
    $JssorDebug$.$Execute(function(){
        if(!w)$JssorDebug$.$Fail("prototype of 'thumbnavigator' not defined.")
            });
    $JssorUtils$.$RemoveChild(b,w);
    c=o.$Lanes||1;
    h=o.$SpacingX;
    p=o.$SpacingY;
    d=$JssorUtils$.$GetStyleWidth(w);
    v=$JssorUtils$.$GetStyleHeight(w);
    m=o.$DisplayPieces
    }
};

var $JssorCaptionSlider$=window.$JssorCaptionSlider$=function(e,t,n){
    function a(e,r){
        function h(e,t){
            var n={};
            
            $JssorUtils$.$Each(o,function(r,i){
                var s=$JssorUtils$.$GetAttribute(e,r+(t||""));
                if(s){
                    var o={};
                    
                    if(r=="t"){
                        o.$Value=s
                        }else if(s.indexOf("%")+1)o.$Percent=$JssorUtils$.$ParseFloat(s)/100;else o.$Value=$JssorUtils$.$ParseFloat(s);
                    n[i]=o
                    }
                });
        return n
        }
        function p(){
        return s[Math.floor(Math.random()*s.length)]
        }
        function d(e){
        var t;
        if(e=="*"){
            t=p()
            }else if(e){
            var n=s[$JssorUtils$.$ParseInt(e)]||s[e];
            if($JssorUtils$.$IsArray(n)){
                if(e!=f){
                    f=e;
                    c[e]=0;
                    l[e]=n[Math.floor(Math.random()*n.length)]
                    }else{
                    c[e]++
                }
                n=l[e];
                if($JssorUtils$.$IsArray(n)){
                    n=n.length&&n[c[e]%n.length];
                    if($JssorUtils$.$IsArray(n)){
                        n=n[Math.floor(Math.random()*n.length)]
                        }
                    }
            }
        t=n;
    if($JssorUtils$.$IsString(t))t=d(t)
        }
        return t
}
var i=[];
var f;
var l=[];
var c=[];
var v=$JssorUtils$.$GetChildren(e);
$JssorUtils$.$Each(v,function(e,s){
    var o=[];
    o.$Elmt=e;
    var f=$JssorUtils$.$GetAttribute(e,"u")=="caption";
    $JssorUtils$.$Each(n?[0,3]:[2],function(i,s){
        if(f){
            var l;
            var c;
            if(i!=2||!$JssorUtils$.$GetAttribute(e,"t3")){
                c=h(e,i);
                if(i==2&&!c.$Transition){
                    c.$Delay=c.$Delay||{
                        $Value:0
                    };
                    
                    c=$JssorUtils$.$Extend(h(e,0),c)
                    }
                }
            if(c&&c.$Transition){
            l=d(c.$Transition.$Value);
            if(l){
                var p=$JssorUtils$.$Extend({
                    $Delay:0,
                    $ScaleHorizontal:1,
                    $ScaleVertical:1
                },l);
                $JssorUtils$.$Each(c,function(e,t){
                    var n=(u[t]||u.$Default).apply(u,[p[t],c[t]]);
                    if(!isNaN(n))p[t]=n
                        });
                if(!s){
                    if(c.$BeginTime)p.$BeginTime=c.$BeginTime.$Value||0;
                    else if((n?t.$PlayInMode:t.$PlayOutMode)&2)p.$BeginTime=0
                        }
                    }
        }
    o.push(p)
    }
    if(r%2&&!s){
    o.$Children=a(e,r+1)
    }
});
i.push(o)
});
return i
}
function f(e,t,r){
    var i={
        $Easing:t.$Easing,
        $Round:t.$Round,
        $During:t.$During,
        $Reverse:n&&!r,
        $Optimize:true
    };
    
    $JssorDebug$.$Execute(function(){
        i.$CaptionAnimator=true
        });
    var s=e;
    var o=$JssorUtils$.$GetParentNode(e);
    var u=$JssorUtils$.$GetStyleWidth(s);
    var a=$JssorUtils$.$GetStyleHeight(s);
    var f=$JssorUtils$.$GetStyleWidth(o);
    var l=$JssorUtils$.$GetStyleHeight(o);
    var c={};
    
    var h={};
    
    var p=t.$ScaleClip||1;
    if(t.$Opacity){
        c.$Opacity=2-t.$Opacity
        }
        i.$OriginalWidth=u;
    i.$OriginalHeight=a;
    if(t.$Zoom||t.$Rotate){
        c.$Zoom=t.$Zoom?t.$Zoom-1:1;
        if($JssorUtils$.$IsBrowserIe9Earlier()||$JssorUtils$.$IsBrowserOpera())c.$Zoom=Math.min(c.$Zoom,2);
        h.$Zoom=1;
        var d=t.$Rotate||0;
        if(d==true)d=1;
        c.$Rotate=d*360;
        h.$Rotate=0
        }else if(t.$Clip){
        var v={
            $Top:0,
            $Right:u,
            $Bottom:a,
            $Left:0
        };
        
        var m=$JssorUtils$.$Extend({},v);
        var g=m.$Offset={};
        
        var y=t.$Clip&4;
        var b=t.$Clip&8;
        var w=t.$Clip&1;
        var E=t.$Clip&2;
        if(y&&b){
            g.$Top=a/2*p;
            g.$Bottom=-g.$Top
            }else if(y)g.$Bottom=-a*p;
        else if(b)g.$Top=a*p;
        if(w&&E){
            g.$Left=u/2*p;
            g.$Right=-g.$Left
            }else if(w)g.$Right=-u*p;
        else if(E)g.$Left=u*p;
        i.$Move=t.$Move;
        c.$Clip=m;
        h.$Clip=v
        }
        {
        var S=t.$FlyDirection;
        var x=0;
        var T=0;
        var N=t.$ScaleHorizontal;
        var C=t.$ScaleVertical;
        if($JssorDirection$.$IsToLeft(S)){
            x-=f*N
            }else if($JssorDirection$.$IsToRight(S)){
            x+=f*N
            }
            if($JssorDirection$.$IsToTop(S)){
            T-=l*C
            }else if($JssorDirection$.$IsToBottom(S)){
            T+=l*C
            }
            if(x||T||i.$Move){
            c.$Left=x+$JssorUtils$.$GetStyleLeft(s);
            c.$Top=T+$JssorUtils$.$GetStyleTop(s)
            }
        }
    var k=t.$Duration;
h=$JssorUtils$.$Extend(h,$JssorUtils$.$GetStyles(s,c));
i.$Setter=$JssorUtils$.$GetStyleSetterEx();
return new $JssorAnimator$(t.$Delay,k,i,s,h,c)
}
function l(e,t){
    $JssorUtils$.$Each(t,function(t,n){
        $JssorDebug$.$Execute(function(){
            if(t.length){
                var e=$JssorUtils$.$GetStyleTop(t.$Elmt);
                var r=$JssorUtils$.$GetStyleLeft(t.$Elmt);
                var i=$JssorUtils$.$GetStyleWidth(t.$Elmt);
                var s=$JssorUtils$.$GetStyleHeight(t.$Elmt);
                var o=null;
                if(isNaN(e))o="style 'top' not specified";
                else if(isNaN(r))o="style 'left' not specified";
                else if(isNaN(i))o="style 'width' not specified";
                else if(isNaN(s))o="style 'height' not specified";
                if(o)$JssorDebug$.$Error("Caption "+(n+1)+" definition error, "+o+".\r\n"+t.$Elmt.outerHTML)
                    }
                });
    var s;
    var o=t.$Elmt;
    var u=t[0];
    var a=t[1];
    if(u){
        s=f(o,u);
        e=s.$Locate($JssorUtils$.$IsUndefined(u.$BeginTime)?e:u.$BeginTime,1)
        }
        e=l(e,t.$Children);
        if(a){
        var c=f(o,a,1);
        c.$Locate(e,1);
        r.$Combine(c);
        i.$Combine(c)
        }
        if(s)r.$Combine(s)
        });
return e
}
$JssorDebug$.$Execute(function(){
    if(!t.$CaptionTransitions){
        $JssorDebug$.$Error("'$CaptionSliderOptions' option error, '$CaptionSliderOptions.$CaptionTransitions' not specified.")
        }
    });
var r=this;
var i;
var s=t.$CaptionTransitions;
var o={
    $Transition:"t",
    $Delay:"d",
    $Duration:"du",
    $ScaleHorizontal:"x",
    $ScaleVertical:"y",
    $Rotate:"r",
    $Zoom:"z",
    $Opacity:"f",
    $BeginTime:"b"
};

var u={
    $Default:function(e,t){
        if(!isNaN(t.$Value))e=t.$Value;else e*=t.$Percent;
        return e
        },
    $Opacity:function(e,t){
        return this.$Default(e-1,t)
        }
    };

u.$Zoom=u.$Opacity;
$JssorAnimator$.call(r,0,0);
r.$Revert=function(){
    r.$GoToPosition(r.$GetPosition_OuterEnd()*(n||0));
    i.$GoToBegin()
    };
    
{
    i=new $JssorAnimator$(0,0);
    l(0,a(e,1))
    }
}

/* jssor.utils.js */
function $JssorPlayerClass$(){
    function n(e){
        function i(t){
            var s=$JssorUtils$.$GetEventSrcElement(t);
            n=s.pInstance;
            $JssorUtils$.$RemoveEvent(s,"dataavailable",i);
            $JssorUtils$.$Each(r,function(e){
                if(e!=n){
                    e.$Remove()
                    }
                });
        e.pTagName=n.tagName;
        r=null
        }
        function s(t){
        var n;
        if(!t.pInstance){
            var s=$JssorUtils$.$GetAttribute(t,"pHandler");
            if($JssorPlayer$[s]){
                $JssorUtils$.$AddEvent(t,"dataavailable",i);
                n=new $JssorPlayer$[s](e,t);
                r.push(n);
                $JssorDebug$.$Execute(function(){
                    if($JssorUtils$.$Type(n.$Remove)!="function"){
                        $JssorDebug$.$Fail("'pRemove' interface not implemented for player handler '"+s+"'.")
                        }
                    })
            }
        }
    return n
}
var t=this;
var n;
var r=[];
t.$InitPlayerController=function(){
    if(!e.pInstance&&!s(e)){
        var t=$JssorUtils$.$GetChildren(e);
        $JssorUtils$.$Each(t,function(e){
            s(e)
            })
        }
    }
}
var e=this;
var t=[];
e.$EVT_SWITCH=21;
e.$FetchPlayers=function(e){
    e=e||document.body;
    var r=$JssorUtils$.$FindChildrenByAttribute(e,"player",null,true);
    $JssorUtils$.$Each(r,function(e){
        if(!t[e.pId]){
            e.pId=t.length;
            t.push(new n(e))
            }
            var r=t[e.pId];
        r.$InitPlayerController()
        })
    }
}
var $JssorPoint$;
(function(){
    $JssorPoint$=function(e,t){
        this.x=typeof e=="number"?e:0;
        this.y=typeof t=="number"?t:0
        };
        
    var e=$JssorPoint$.prototype;
    e.$Plus=function(e){
        return new $JssorPoint$(this.x+e.x,this.y+e.y)
        };
        
    e.$Minus=function(e){
        return new $JssorPoint$(this.x-e.x,this.y-e.y)
        };
        
    e.$Times=function(e){
        return new $JssorPoint$(this.x*e,this.y*e)
        };
        
    e.$Divide=function(e){
        return new $JssorPoint$(this.x/e,this.y/e)
        };
        
    e.$Negate=function(){
        return new $JssorPoint$(-this.x,-this.y)
        };
        
    e.$DistanceTo=function(e){
        return Math.sqrt(Math.pow(this.x-e.x,2)+Math.pow(this.y-e.y,2))
        };
        
    e.$Apply=function(e){
        return new $JssorPoint$(e(this.x),e(this.y))
        };
        
    e.$Equals=function(e){
        return e instanceof $JssorPoint$&&this.x===e.x&&this.y===e.y
        };
        
    e.$ToString=function(){
        return"("+this.x+","+this.y+")"
        }
    })();
var $JssorEasing$=window.$JssorEasing$={
    $EaseLinear:function(e){
        return e
        },
    $EaseGoBack:function(e){
        return 1-Math.abs((e*=2)-1)
        },
    $EaseSwing:function(e){
        return-Math.cos(e*Math.PI)/2+.5
        },
    $EaseInQuad:function(e){
        return e*e
        },
    $EaseOutQuad:function(e){
        return-e*(e-2)
        },
    $EaseInOutQuad:function(e){
        return(e*=2)<1?1/2*e*e:-1/2*(--e*(e-2)-1)
        },
    $EaseInCubic:function(e){
        return e*e*e
        },
    $EaseOutCubic:function(e){
        return(e-=1)*e*e+1
        },
    $EaseInOutCubic:function(e){
        return(e*=2)<1?1/2*e*e*e:1/2*((e-=2)*e*e+2)
        },
    $EaseInQuart:function(e){
        return e*e*e*e
        },
    $EaseOutQuart:function(e){
        return-((e-=1)*e*e*e-1)
        },
    $EaseInOutQuart:function(e){
        return(e*=2)<1?1/2*e*e*e*e:-1/2*((e-=2)*e*e*e-2)
        },
    $EaseInQuint:function(e){
        return e*e*e*e*e
        },
    $EaseOutQuint:function(e){
        return(e-=1)*e*e*e*e+1
        },
    $EaseInOutQuint:function(e){
        return(e*=2)<1?1/2*e*e*e*e*e:1/2*((e-=2)*e*e*e*e+2)
        },
    $EaseInSine:function(e){
        return 1-Math.cos(e*Math.PI/2)
        },
    $EaseOutSine:function(e){
        return Math.sin(e*Math.PI/2)
        },
    $EaseInOutSine:function(e){
        return-1/2*(Math.cos(Math.PI*e)-1)
        },
    $EaseInExpo:function(e){
        return e==0?0:Math.pow(2,10*(e-1))
        },
    $EaseOutExpo:function(e){
        return e==1?1:-Math.pow(2,-10*e)+1
        },
    $EaseInOutExpo:function(e){
        return e==0||e==1?e:(e*=2)<1?1/2*Math.pow(2,10*(e-1)):1/2*(-Math.pow(2,-10*--e)+2)
        },
    $EaseInCirc:function(e){
        return-(Math.sqrt(1-e*e)-1)
        },
    $EaseOutCirc:function(e){
        return Math.sqrt(1-(e-=1)*e)
        },
    $EaseInOutCirc:function(e){
        return(e*=2)<1?-1/2*(Math.sqrt(1-e*e)-1):1/2*(Math.sqrt(1-(e-=2)*e)+1)
        },
    $EaseInElastic:function(e){
        if(!e||e==1)return e;
        var t=.3,n=.075;
        return-(Math.pow(2,10*(e-=1))*Math.sin((e-n)*2*Math.PI/t))
        },
    $EaseOutElastic:function(e){
        if(!e||e==1)return e;
        var t=.3,n=.075;
        return Math.pow(2,-10*e)*Math.sin((e-n)*2*Math.PI/t)+1
        },
    $EaseInOutElastic:function(e){
        if(!e||e==1)return e;
        var t=.45,n=.1125;
        return(e*=2)<1?-.5*Math.pow(2,10*(e-=1))*Math.sin((e-n)*2*Math.PI/t):Math.pow(2,-10*(e-=1))*Math.sin((e-n)*2*Math.PI/t)*.5+1
        },
    $EaseInBack:function(e){
        var t=1.70158;
        return e*e*((t+1)*e-t)
        },
    $EaseOutBack:function(e){
        var t=1.70158;
        return(e-=1)*e*((t+1)*e+t)+1
        },
    $EaseInOutBack:function(e){
        var t=1.70158;
        return(e*=2)<1?1/2*e*e*(((t*=1.525)+1)*e-t):1/2*((e-=2)*e*(((t*=1.525)+1)*e+t)+2)
        },
    $EaseInBounce:function(e){
        return 1-$JssorEasing$.$EaseOutBounce(1-e)
        },
    $EaseOutBounce:function(e){
        return e<1/2.75?7.5625*e*e:e<2/2.75?7.5625*(e-=1.5/2.75)*e+.75:e<2.5/2.75?7.5625*(e-=2.25/2.75)*e+.9375:7.5625*(e-=2.625/2.75)*e+.984375
        },
    $EaseInOutBounce:function(e){
        return e<1/2?$JssorEasing$.$EaseInBounce(e*2)*.5:$JssorEasing$.$EaseOutBounce(e*2-1)*.5+.5
        },
    $EaseInWave:function(e){
        return 1-Math.cos(e*Math.PI*2)
        },
    $EaseOutWave:function(e){
        return Math.sin(e*Math.PI*2)
        },
    $EaseOutJump:function(e){
        return 1-((e*=2)<1?(e=1-e)*e*e:(e-=1)*e*e)
        },
    $EaseInJump:function(e){
        return(e*=2)<1?e*e*e:(e=2-e)*e*e
        }
    };

var $JssorDirection$=window.$JssorDirection$={
    $TO_LEFT:1,
    $TO_RIGHT:2,
    $TO_TOP:4,
    $TO_BOTTOM:8,
    $HORIZONTAL:3,
    $VERTICAL:12,
    $LEFTRIGHT:3,
    $TOPBOTOM:12,
    $TOPLEFT:5,
    $TOPRIGHT:6,
    $BOTTOMLEFT:9,
    $BOTTOMRIGHT:10,
    $AROUND:15,
    $GetDirectionHorizontal:function(e){
        return e&3
        },
    $GetDirectionVertical:function(e){
        return e&12
        },
    $ChessHorizontal:function(e){
        return(~e&3)+(e&12)
        },
    $ChessVertical:function(e){
        return(~e&12)+(e&3)
        },
    $IsToLeft:function(e){
        return(e&3)==1
        },
    $IsToRight:function(e){
        return(e&3)==2
        },
    $IsToTop:function(e){
        return(e&12)==4
        },
    $IsToBottom:function(e){
        return(e&12)==8
        },
    $IsHorizontal:function(e){
        return(e&3)>0
        },
    $IsVertical:function(e){
        return(e&12)>0
        }
    };

var $JssorKeyCode$={
    $BACKSPACE:8,
    $COMMA:188,
    $DELETE:46,
    $DOWN:40,
    $END:35,
    $ENTER:13,
    $ESCAPE:27,
    $HOME:36,
    $LEFT:37,
    $NUMPAD_ADD:107,
    $NUMPAD_DECIMAL:110,
    $NUMPAD_DIVIDE:111,
    $NUMPAD_ENTER:108,
    $NUMPAD_MULTIPLY:106,
    $NUMPAD_SUBTRACT:109,
    $PAGE_DOWN:34,
    $PAGE_UP:33,
    $PERIOD:190,
    $RIGHT:39,
    $SPACE:32,
    $TAB:9,
    $UP:38
};

var $JssorAlignment$={
    $TopLeft:17,
    $TopCenter:18,
    $TopRight:20,
    $MiddleLeft:33,
    $MiddleCenter:34,
    $MiddleRight:36,
    $BottomLeft:65,
    $BottomCenter:66,
    $BottomRight:68,
    $IsTop:function(e){
        return e&16>0
        },
    $IsMiddle:function(e){
        return e&32>0
        },
    $IsBottom:function(e){
        return e&64>0
        },
    $IsLeft:function(e){
        return e&1>0
        },
    $IsCenter:function(e){
        return e&2>0
        },
    $IsRight:function(e){
        return e&4>0
        }
    };

var $JssorMatrix$;
var $JssorBrowser$={
    $UNKNOWN:0,
    $IE:1,
    $FIREFOX:2,
    $SAFARI:3,
    $CHROME:4,
    $OPERA:5
};

var $ROWSER_UNKNOWN$=0;
var $ROWSER_IE$=1;
var $ROWSER_FIREFOX$=2;
var $ROWSER_SAFARI$=3;
var $ROWSER_CHROME$=4;
var $ROWSER_OPERA$=5;
var $JssorAnimator$;
var $JssorUtils$=window.$JssorUtils$=new function(){
    function h(){
        if(!r){
            if(a=="Microsoft Internet Explorer"&&!!window.attachEvent&&!!window.ActiveXObject){
                var e=l.indexOf("MSIE");
                r=$JssorBrowser$.$IE;
                s=parseFloat(l.substring(e+5,l.indexOf(";",e)));
                i=document.documentMode||s
                }else if(a=="Netscape"&&!!window.addEventListener){
                var t=l.indexOf("Firefox");
                var n=l.indexOf("Safari");
                var o=l.indexOf("Chrome");
                var f=l.indexOf("AppleWebKit");
                if(t>=0){
                    r=$JssorBrowser$.$FIREFOX;
                    i=parseFloat(l.substring(t+8))
                    }else if(n>=0){
                    var c=l.substring(0,n).lastIndexOf("/");
                    r=o>=0?$JssorBrowser$.$CHROME:$JssorBrowser$.$SAFARI;
                    i=parseFloat(l.substring(c+1,n))
                    }
                    if(f>=0)u=parseFloat(l.substring(f+12))
                    }else{
                var h=/(opera)(?:.*version|)[ \/]([\w.]+)/i.exec(l);
                if(h){
                    r=$JssorBrowser$.$OPERA;
                    i=parseFloat(h[2])
                    }
                }
        }
}
function p(){
    h();
    return r==$ROWSER_IE$
    }
    function d(){
    return p()&&(i<6||document.compatMode=="BackCompat")
    }
    function v(){
    h();
    return r==$ROWSER_FIREFOX$
    }
    function m(){
    h();
    return r==$ROWSER_SAFARI$
    }
    function g(){
    h();
    return r==$ROWSER_CHROME$
    }
    function y(){
    h();
    return r==$ROWSER_OPERA$
    }
    function b(){
    return m()&&u>534&&u<535
    }
    function w(){
    return m()&&u<535
    }
    function E(){
    return p()&&i<9
    }
    function x(t){
    if(!S){
        k(["transform","WebkitTransform","msTransform","MozTransform","OTransform"],function(n){
            if(!e.$IsUndefined(t.style[n])){
                S=n;
                return true
                }
            });
    S=S||"transform"
    }
    return S
}
function T(e,t){
    if(t&&e!=document.body){
        return document.body
        }else{
        return e.offsetParent
        }
    }
function N(e){
    return Object.prototype.toString.call(e)
    }
    function k(e,t){
    if(N(e)=="[object Array]"){
        for(var n=0;n<e.length;n++){
            if(t(e[n],n,e)){
                break
            }
        }
        }else{
    for(var r in e){
        if(t(e[r],r,e)){
            break
        }
    }
    }
}
function L(){
    if(!C){
        C={};
        
        k(["Boolean","Number","String","Function","Array","Date","RegExp","Object"],function(e){
            C["[object "+e+"]"]=e.toLowerCase()
            })
        }
        return C
    }
    function A(e){
    return e==null?String(e):L()[N(e)]||"object"
    }
    function O(t){
    if(!t||A(t)!=="object"||t.nodeType||e.$IsWindow(t)){
        return false
        }
        var n=Object.prototype.hasOwnProperty;
    try{
        if(t.constructor&&!n.call(t,"constructor")&&!n.call(t.constructor.prototype,"isPrototypeOf")){
            return false
            }
        }catch(r){
    return false
    }
    var i;
for(i in t){}
    return i===undefined||n.call(t,i)
}
function M(e,t){
    setTimeout(e,t||0)
    }
    function _(e,t){
    var n=t.exec(e);
    if(n){
        var r=e.substr(0,n.index);
        var i=e.substr(n.lastIndex+1,e.length-(n.lastIndex+1));
        e=r+i
        }
        return e
    }
    function D(e,t,n){
    var r=!e||e=="inherit"?"":e;
    k(t,function(e){
        var t=e.exec(r);
        if(t){
            var n=r.substr(0,t.index);
            var i=r.substr(t.lastIndex+1,r.length-(t.lastIndex+1));
            r=n+i
            }
        });
r=n+(r.indexOf(" ")!=0?" ":"")+r;
return r
}
function P(e,t){
    if(i<9){
        e.style.filter=t
        }
    }
function H(t,n,r){
    if(o<9){
        var i=t.style.filter;
        var s=new RegExp(/[\s]*progid:DXImageTransform\.Microsoft\.Matrix\([^\)]*\)/g);
        var u=n?"progid:DXImageTransform.Microsoft.Matrix("+"M11="+n[0][0]+", M12="+n[0][1]+", M21="+n[1][0]+", M22="+n[1][1]+", SizingMethod='auto expand')":"";
        var a=D(i,[s],u);
        P(t,a);
        e.$SetStyleMarginTop(t,r.y);
        e.$SetStyleMarginLeft(t,r.x)
        }
    }
function B(t,n){
    var r=n.$Rotate||0;
    var i=n.$Scale||1;
    if(E()){
        var s=e.$CreateMatrix(r/180*Math.PI,i,i);
        H(t,!r&&i==1?null:s,e.$GetMatrixOffset(s,n.$OriginalWidth,n.$OriginalHeight))
        }else{
        var o=x(t);
        if(o){
            var a="rotate("+r%360+"deg) scale("+i+")";
            if($JssorUtils$.$IsBrowserChrome()&&u>535)a+=" perspective(2000px)";
            t.style[o]=a
            }
        }
}
function q(e){
    var t=true;
    var n=d()?e.document.body:e.document.documentElement;
    if(n){
        var r=n.offsetWidth-j;
        var i=n.offsetHeight-F;
        if(r||i){
            j+=r;
            F+=i
            }else t=false
            }
            t&&k(I,function(e){
        e()
        })
    }
    function U(e,t,n,r){
    if(!n)n="u";
    for(e=e?e.firstChild:null;e;e=e.nextSibling){
        if(e.nodeType==1){
            if(e.getAttribute(n)==t)return e;
            if(r){
                var i=U(e,t,n,r);
                if(i)return i
                    }
                }
    }
}
function z(e,t,n,r){
    if(!n)n="u";
    var i=[];
    for(e=e?e.firstChild:null;e;e=e.nextSibling){
        if(e.nodeType==1){
            if(e.getAttribute(n)==t)i.push(e);
            if(r){
                var s=z(e,t,n,r);
                if(s.length)i=i.concat(s)
                    }
                }
    }
    return i
}
function W(e,t,n){
    for(e=e?e.firstChild:null;e;e=e.nextSibling){
        if(e.nodeType==1){
            if(e.tagName==t)return e;
            if(n){
                var r=W(e,t,n);
                if(r)return r
                    }
                }
    }
}
function X(e,t,n){
    var r=[];
    for(e=e?e.firstChild:null;e;e=e.nextSibling){
        if(e.nodeType==1){
            if(!t||e.tagName==t)r.push(e);
            if(n){
                var i=X(e,t,true);
                if(i.length)r=r.concat(i)
                    }
                }
    }
    return r
}
function V(e,t,n){
    t.onload=null;
    t.abort=null;
    if(e)e(t,n)
        }
        function K(t){
    function o(){
        var e=r;
        if(i){
            e+="dn"
            }else if(s){
            e+="av"
            }
            $JssorUtils$.$SetClassName(t,e)
        }
        function u(e){
        $.push(n);
        i=true;
        o()
        }
        var n=this;
    var r;
    var i;
    var s;
    n.$MouseUp=function(){
        i=false;
        o()
        };
        
    n.$Activate=function(e){
        s=e;
        o()
        };
        
    {
        t=e.$GetElement(t);
        if(!$){
            e.$AddEventBrowserMouseUp(function(){
                var e=$;
                $=[];
                k(e,function(e){
                    e.$MouseUp()
                    })
                });
            $=[]
            }
            r=e.$GetClassName(t);
        $JssorUtils$.$AddEvent(t,"mousedown",u)
        }
    }
function Y(){
    return G
    }
    function Z(){
    Y();
    G.$Transform=G.$Transform;
    return G
    }
    var e=this;
var t=["Msxml2.XMLHTTP","Msxml3.XMLHTTP","Microsoft.XMLHTTP"];
var n={
    bmp:false,
    jpeg:true,
    jpg:true,
    png:true,
    tif:false,
    wdp:false
};

var r=$JssorBrowser$.$UNKNOWN;
var i=0;
var s=0;
var o=0;
var u=0;
var a=navigator.appName;
var f=navigator.appVersion;
var l=navigator.userAgent;
var c={};

var S;
{}
var C;
e.$IsBrowserIE=p;
e.$IsBrowserIeQuirks=d;
e.$IsBrowserFireFox=v;
e.$IsBrowserSafari=m;
e.$IsBrowserChrome=g;
e.$IsBrowserOpera=y;
e.$IsBrowserBadTransform=b;
e.$IsBrowserSafeHWA=w;
e.$IsBrowserIe9Earlier=E;
e.$GetBrowserVersion=function(){
    return i
    };
    
e.$GetBrowserEngineVersion=function(){
    return s||i
    };
    
e.$GetWebKitVersion=function(){
    return u
    };
    
e.$Delay=M;
e.$GetElement=function(t){
    if(e.$IsString(t)){
        t=document.getElementById(t)
        }
        return t
    };
    
e.$GetElementPosition=function(t){
    t=e.$GetElement(t);
    var n=new $JssorPoint$;
    while(t){
        n.x+=t.offsetLeft;
        n.y+=t.offsetTop;
        var r=e.$GetElementStyle(t).position=="fixed";
        if(r){
            n=n.$Plus(e.$GetPageScroll(window))
            }
            t=T(t,r)
        }
        return n
    };
    
e.$GetElementSize=function(t){
    t=e.$GetElement(t);
    return new $JssorPoint$(t.clientWidth,t.clientHeight)
    };
    
e.$GetElementStyle=function(t){
    t=e.$GetElement(t);
    if(t.currentStyle){
        return t.currentStyle
        }else if(window.getComputedStyle){
        return window.getComputedStyle(t,"")
        }else{
        $JssorDebug$.$Fail("Unknown elmt style, no known technique.")
        }
    };

e.$GetEvent=function(e){
    return e?e:window.event
    };
    
e.$GetEventSrcElement=function(t){
    t=e.$GetEvent(t);
    return t.target||t.srcElement||document
    };
    
e.$GetEventDstElement=function(t){
    t=e.$GetEvent(t);
    return t.relatedTarget||t.toElement
    };
    
e.$GetMousePosition=function(t){
    t=e.$GetEvent(t);
    var n=new $JssorPoint$;
    if(t.type=="DOMMouseScroll"&&v()&&i<3){
        n.x=t.screenX;
        n.y=t.screenY
        }else if(typeof t.pageX=="number"){
        n.x=t.pageX;
        n.y=t.pageY
        }else if(typeof t.clientX=="number"){
        n.x=t.clientX+document.body.scrollLeft+document.documentElement.scrollLeft;
        n.y=t.clientY+document.body.scrollTop+document.documentElement.scrollTop
        }else{
        $JssorDebug$.$Fail("Unknown event mouse position, no known technique.")
        }
        return n
    };
    
e.$GetMouseScroll=function(t){
    t=e.$GetEvent(t);
    var n=0;
    if(typeof t.wheelDelta=="number"){
        n=t.wheelDelta
        }else if(typeof t.detail=="number"){
        n=t.detail*-1
        }else{
        $JssorDebug$.$Fail("Unknown event mouse scroll, no known technique.")
        }
        return n?n/Math.abs(n):0
    };
    
e.$GetPageScroll=function(e){
    var t=new $JssorPoint$;
    var n=e.document.documentElement||{};
    
    var r=e.document.body||{};
    
    if(typeof e.pageXOffset=="number"){
        t.x=e.pageXOffset;
        t.y=e.pageYOffset
        }else if(r.scrollLeft||r.scrollTop){
        t.x=r.scrollLeft;
        t.y=r.scrollTop
        }else if(n.scrollLeft||n.scrollTop){
        t.x=n.scrollLeft;
        t.y=n.scrollTop
        }
        return t
    };
    
e.$GetWindowSize=function(e){
    var t=new $JssorPoint$;
    var n=d()?e.document.body:e.document.documentElement;
    t.x=n.clientWidth;
    t.y=n.clientHeight;
    return t
    };
    
e.$GetStyleOpacity=function(e){
    if(p()&&s<9){
        var t=/opacity=([^)]*)/.exec(e.style.filter||"");
        return t?parseFloat(t[1])/100:1
        }else return parseFloat(e.style.opacity||"1")
        };
        
e.$SetStyleOpacity=e.$setElementOpacity=function(e,t,n){
    if(p()&&s<9){
        var r=e.style.filter||"";
        var i=new RegExp(/[\s]*alpha\([^\)]*\)/g);
        var o=Math.round(100*t);
        var u="";
        if(o<100||n){
            u="alpha(opacity="+o+") "
            }
            var a=D(r,[i],u);
        P(e,a)
        }else{
        e.style.opacity=t==1?"":Math.round(t*100)/100
        }
    };

e.$SetStyleTransform=function(t,n){
    if(b()){
        M(e.$CreateCallback(null,B,t,n))
        }else{
        B(t,n)
        }
    };

e.$SetStyleTransformOrigin=function(e,t){
    var n=x(e);
    if(n)e.style[n+"Origin"]=t
        };
        
e.$SetStyleScale=function(e,t){
    if(p()&&s<9||s<10&&d()){
        e.style.zoom=t==1?"":t
        }else{
        var n=x(e);
        if(n){
            var r="scale("+t+")";
            var i=e.style[n];
            var o=new RegExp(/[\s]*scale\(.*?\)/g);
            var u=D(i,[o],r);
            e.style[n]=u
            }
        }
};

e.$EnableHWA=function(e){
    if(!e.style[x(e)]||e.style[x(e)]=="none")e.style[x(e)]="perspective(2000px)"
        };
        
e.$DisableHWA=function(e){
    e.style[x(e)]="none"
    };
    
e.$GetStyleFloat=function(e){
    return p()?e.style.styleFloat:e.style.cssFloat
    };
    
e.$SetStyleFloat=function(e,t){
    if(p())e.style.styleFloat=t;else e.style.cssFloat=t
        };
        
var j=0;
var F=0;
var I;
e.$OnWindowResize=function(t,n){
    if(p()&&s<9){
        if(!I){
            I=[n];
            n=e.$CreateCallback(null,q,t)
            }else{
            I.push(n);
            return
        }
    }
    e.$AddEvent(t,"resize",n)
};

e.$AddEvent=function(t,n,r,i){
    t=e.$GetElement(t);
    if(t.addEventListener){
        if(n=="mousewheel"){
            t.addEventListener("DOMMouseScroll",r,i)
            }
            t.addEventListener(n,r,i)
        }else if(t.attachEvent){
        t.attachEvent("on"+n,r);
        if(i&&t.setCapture){
            t.setCapture()
            }
        }
    $JssorDebug$.$Execute(function(){
    if(!t.addEventListener&&!t.attachEvent){
        $JssorDebug$.$Fail("Unable to attach event handler, no known technique.")
        }
    })
};

e.$RemoveEvent=function(t,n,r,i){
    t=e.$GetElement(t);
    if(t.removeEventListener){
        if(n=="mousewheel"){
            t.removeEventListener("DOMMouseScroll",r,i)
            }
            t.removeEventListener(n,r,i)
        }else if(t.detachEvent){
        t.detachEvent("on"+n,r);
        if(i&&t.releaseCapture){
            t.releaseCapture()
            }
        }
    $JssorDebug$.$Execute(function(){
    if(!t.removeEventListener&&!t.detachEvent){
        $JssorDebug$.$Fail("Unable to detach event handler, no known technique.")
        }
    })
};

e.$FireEvent=function(e,t){
    $JssorDebug$.$Execute(function(){
        if(!document.createEvent&&!document.createEventObject){
            $JssorDebug$.$Fail("Unable to fire event, no known technique.")
            }
            if(!e.dispatchEvent&&!e.fireEvent){
            $JssorDebug$.$Fail("Unable to fire event, no known technique.")
            }
        });
var n;
if(document.createEvent){
    n=document.createEvent("HTMLEvents");
    n.initEvent(t,false,false);
    e.dispatchEvent(n)
    }else{
    var r="on"+t;
    n=document.createEventObject();
    e.fireEvent(r,n)
    }
};

e.$AddEventBrowserMouseUp=function(t,n){
    e.$AddEvent(E()?document:window,"mouseup",t,n)
    };
    
e.$RemoveEventBrowserMouseUp=function(t,n){
    e.$RemoveEvent(E()?document:window,"mouseup",t,n)
    };
    
e.$AddEventBrowserMouseDown=function(t,n){
    e.$AddEvent(E()?document:window,"mousedown",t,n)
    };
    
e.$RemoveEventBrowserMouseDown=function(t,n){
    e.$RemoveEvent(E()?document:window,"mousedown",t,n)
    };
    
e.$CancelEvent=function(t){
    t=e.$GetEvent(t);
    if(t.preventDefault){
        t.preventDefault()
        }
        t.cancel=true;
    t.returnValue=false
    };
    
e.$StopEvent=function(t){
    t=e.$GetEvent(t);
    if(t.stopPropagation){
        t.stopPropagation()
        }
        t.cancelBubble=true
    };
    
e.$CreateCallback=function(e,t){
    var n=[];
    for(var r=2;r<arguments.length;r++){
        n.push(arguments[r])
        }
        var i=function(){
        var r=n.concat([]);
        for(var i=0;i<arguments.length;i++){
            r.push(arguments[i])
            }
            return t.apply(e,r)
        };
        
    $JssorDebug$.$LiveStamp(i,"callback_"+($JssorUtils$.$GetNow()&11111111));
    return i
    };
    
var R;
e.$FreeElement=function(t){
    if(!R)R=e.$CreateDivElement();
    if(t){
        $JssorUtils$.$AppendChild(R,t);
        $JssorUtils$.$ClearInnerHtml(R)
        }
    };

e.$SetInnerText=function(t,n){
    var r=document.createTextNode(n);
    e.$ClearInnerHtml(t);
    t.appendChild(r)
    };
    
e.$GetInnerText=function(e){
    return e.textContent||e.innerText
    };
    
e.$GetInnerHtml=function(e){
    return e.innerHTML
    };
    
e.$SetInnerHtml=function(e,t){
    e.innerHTML=t
    };
    
e.$ClearInnerHtml=function(e){
    e.innerHTML=""
    };
    
e.$EncodeHtml=function(t){
    var n=e.$CreateDivElement();
    e.$SetInnerText(n,t);
    return e.$GetInnerHtml(n)
    };
    
e.$DecodeHtml=function(t){
    var n=e.$CreateDivElement();
    e.$SetInnerHtml(n,t);
    return e.$GetInnerText(n)
    };
    
e.$SelectElement=function(e){
    var t;
    if(window.getSelection){
        t=window.getSelection()
        }
        var n=null;
    if(document.createRange){
        n=document.createRange();
        n.selectNode(e)
        }else{
        n=document.body.createTextRange();
        n.moveToElementText(e);
        n.select()
        }
        if(t)t.addRange(n)
        };
        
e.$DeselectElements=function(){
    if(document.selection){
        document.selection.empty()
        }else if(window.getSelection){
        window.getSelection().removeAllRanges()
        }
    };

e.$GetChildren=function(e){
    var t=[];
    for(var n=e.firstChild;n;n=n.nextSibling){
        if(n.nodeType==1){
            t.push(n)
            }
        }
    return t
};

e.$FindFirstChildByAttribute=U;
e.$FindChildrenByAttribute=z;
e.$FindFirstChildByTag=W;
e.$FindChildrenByTag=X;
e.$GetElementsByTagName=function(e,t){
    return e.getElementsByTagName(t)
    };
    
e.$Extend=function(e){
    for(var t=1;t<arguments.length;t++){
        var n=arguments[t];
        if(n){
            for(var r in n){
                e[r]=n[r]
                }
            }
            }
    return e
};

e.$Unextend=function(e,t){
    $JssorDebug$.$Assert(t);
    var n={};
    
    for(var r in e){
        if(e[r]!=t[r]){
            n[r]=e[r]
            }
        }
    return n
};

e.$IsUndefined=function(e){
    return A(e)=="undefined"
    };
    
e.$IsFunction=function(e){
    return A(e)=="function"
    };
    
e.$IsArray=Array.isArray||function(e){
    return A(e)=="array"
    };
    
e.$IsString=function(e){
    return A(e)=="string"
    };
    
e.$IsNumeric=function(e){
    return!isNaN(parseFloat(e))&&isFinite(e)
    };
    
e.$IsWindow=function(e){
    return e!=null&&e==e.window
    };
    
e.$Type=A;
e.$Each=k;
e.$IsPlainObject=O;
e.$CreateDivElement=function(t){
    return e.$CreateElement("DIV",t)
    };
    
e.$CreateSpanElement=function(t){
    return e.$CreateElement("SPAN",t)
    };
    
e.$CreateElement=function(e,t){
    t=t||document;
    return t.createElement(e)
    };
    
e.$EmptyFunction=function(){};

e.$GetAttribute=function(e,t){
    return e.getAttribute(t)
    };
    
e.$SetAttribute=function(e,t,n){
    e.setAttribute(t,n)
    };
    
e.$GetClassName=function(e){
    return e.className
    };
    
e.$SetClassName=function(e,t){
    e.className=t?t:""
    };
    
e.$GetStyleCursor=function(e){
    return e.style.cursor
    };
    
e.$SetStyleCursor=function(e,t){
    e.style.cursor=t
    };
    
e.$GetStyleDisplay=function(e){
    return e.style.display
    };
    
e.$SetStyleDisplay=function(e,t){
    e.style.display=t||""
    };
    
e.$GetStyleOverflow=function(e){
    return e.style.overflow
    };
    
e.$SetStyleOverflow=function(e,t){
    e.style.overflow=t
    };
    
e.$GetStyleOverflowX=function(e){
    return e.style.overflowX
    };
    
e.$SetStyleOverflowX=function(e,t){
    e.style.overflowX=t
    };
    
e.$GetStyleOverflowY=function(e){
    return e.style.overflowY
    };
    
e.$SetStyleOverflowY=function(e,t){
    e.style.overflowY=t
    };
    
e.$GetParentNode=function(e){
    return e.parentNode
    };
    
e.$HideElement=function(t){
    e.$SetStyleDisplay(t,"none")
    };
    
e.$HideElements=function(t){
    for(var n=0;n<t.length;n++){
        e.$HideElement(t[n])
        }
    };
    
e.$ShowElement=function(t,n){
    e.$SetStyleDisplay(t,n==false?"none":"")
    };
    
e.$ShowElements=function(t){
    for(var n=0;n<t.length;n++){
        e.$ShowElement(t[n])
        }
    };
    
e.$GetStylePosition=function(e){
    return e.style.position
    };
    
e.$SetStylePosition=function(e,t){
    e.style.position=t
    };
    
e.$GetStyleTop=function(e){
    return parseInt(e.style.top,10)
    };
    
e.$SetStyleTop=function(e,t){
    e.style.top=t+"px"
    };
    
e.$GetStyleRight=function(e){
    return parseInt(e.style.right,10)
    };
    
e.$SetStyleRight=function(e,t){
    e.style.right=t+"px"
    };
    
e.$GetStyleBottom=function(e){
    return parseInt(e.style.bottom,10)
    };
    
e.$SetStyleBottom=function(e,t){
    e.style.bottom=t+"px"
    };
    
e.$GetStyleLeft=function(e){
    return parseInt(e.style.left,10)
    };
    
e.$SetStyleLeft=function(e,t){
    e.style.left=t+"px"
    };
    
e.$GetStyleWidth=function(e){
    return parseInt(e.style.width,10)
    };
    
e.$SetStyleWidth=function(e,t){
    e.style.width=Math.max(t,0)+"px"
    };
    
e.$GetStyleHeight=function(e){
    return parseInt(e.style.height,10)
    };
    
e.$SetStyleHeight=function(e,t){
    e.style.height=Math.max(t,0)+"px"
    };
    
e.$GetStyleCssText=function(e){
    return e.style.cssText
    };
    
e.$SetStyleCssText=function(e,t){
    e.style.cssText=t
    };
    
e.$RemoveAttribute=function(e,t){
    e.removeAttribute(t)
    };
    
e.$GetBorderWidth=function(e){
    return parseInt(e.style.borderWidth,10)
    };
    
e.$SetBorderWdith=function(e,t){
    e.style.width=t+"px"
    };
    
e.$GetStyleMarginLeft=function(e){
    return parseInt(e.style.marginLeft,10)||0
    };
    
e.$SetStyleMarginLeft=function(e,t){
    e.style.marginLeft=t+"px"
    };
    
e.$GetStyleMarginTop=function(e){
    return parseInt(e.style.marginTop,10)||0
    };
    
e.$SetStyleMarginTop=function(e,t){
    e.style.marginTop=t+"px"
    };
    
e.$GetStyleMarginBottom=function(e){
    return parseInt(e.style.marginBottom,10)||0
    };
    
e.$SetStyleMarginBottom=function(e,t){
    e.style.marginBottom=t+"px"
    };
    
e.$GetStyleMarginRight=function(e){
    return parseInt(e.style.marginRight,10)||0
    };
    
e.$SetStyleMarginRight=function(e,t){
    e.style.marginRight=t+"px"
    };
    
e.$GetStyleBorder=function(e){
    return e.style.border
    };
    
e.$SetStyleBorder=function(e,t){
    e.style.border=t
    };
    
e.$GetStyleBorderWidth=function(e){
    return parseInt(e.style.borderWidth)
    };
    
e.$SetStyleBorderWidth=function(e,t){
    e.style.borderWidth=t+"px"
    };
    
e.$GetStyleBorderStyle=function(e){
    return e.style.borderStyle
    };
    
e.$SetStyleBorderStyle=function(e,t){
    e.style.borderStyle=t
    };
    
e.$GetStyleBorderColor=function(e){
    return e.style.borderColor
    };
    
e.$SetStyleBorderColor=function(e,t){
    e.style.borderColor=t
    };
    
e.$GetStyleVibility=function(e){
    return e.style.vibility
    };
    
e.$SetStyleVisibility=function(e,t){
    e.style.visibility=t
    };
    
e.$GetStyleZIndex=function(e){
    return parseInt(e.style.zIndex)||0
    };
    
e.$SetStyleZIndex=function(e,t){
    e.style.zIndex=Math.ceil(t)
    };
    
e.$GetStyleBackgroundColor=function(e){
    return e.style.backgroundColor
    };
    
e.$SetStyleBackgroundColor=function(e,t){
    e.style.backgroundColor=t
    };
    
e.$GetStyleColor=function(e){
    return e.style.color
    };
    
e.$SetStyleColor=function(e,t){
    e.style.color=t
    };
    
e.$GetStyleBackgroundImage=function(e){
    return e.style.backgroundImage
    };
    
e.$SetStyleBackgroundImage=function(e,t){
    e.style.backgroundImage=t
    };
    
e.$CanClearClip=function(){
    return p()&&i<10
    };
    
e.$SetStyleClip=function(t,n){
    if(n){
        t.style.clip="rect("+Math.round(n.$Top)+"px "+Math.round(n.$Right)+"px "+Math.round(n.$Bottom)+"px "+Math.round(n.$Left)+"px)"
        }else{
        var r=e.$GetStyleCssText(t);
        var i=[new RegExp(/[\s]*clip: rect\(.*?\)[;]?/i),new RegExp(/[\s]*cliptop: .*?[;]?/i),new RegExp(/[\s]*clipright: .*?[;]?/i),new RegExp(/[\s]*clipbottom: .*?[;]?/i),new RegExp(/[\s]*clipleft: .*?[;]?/i)];
        var s=D(r,i,"");
        $JssorUtils$.$SetStyleCssText(t,s)
        }
    };

e.$GetStyleZoom=function(e){
    return e.style.zoom
    };
    
e.$SetStyleZoom=function(e,t){
    return e.style.zoom=t
    };
    
e.$SetStyleClear=function(e,t){
    e.style.clear=t
    };
    
e.$GetNow=function(){
    return(new Date).getTime()
    };
    
e.$AppendChild=function(e,t){
    e.appendChild(t)
    };
    
e.$AppendChildren=function(t,n){
    k(n,function(n){
        e.$AppendChild(t,n)
        })
    };
    
e.$InsertBefore=function(e,t,n){
    e.insertBefore(t,n)
    };
    
e.$InsertAdjacentHtml=function(e,t,n){
    e.insertAdjacentHTML(t,n)
    };
    
e.$RemoveChild=function(e,t){
    e.removeChild(t)
    };
    
e.$RemoveChildren=function(t,n){
    k(n,function(n){
        e.$RemoveChild(t,n)
        })
    };
    
e.$ClearChildren=function(t){
    e.$RemoveChildren(t,e.$GetChildren(t))
    };
    
e.$ParseInt=function(e,t){
    return parseInt(e,t||10)
    };
    
e.$ParseFloat=function(e){
    return parseFloat(e)
    };
    
e.$IsChild=function(e,t){
    var n=document.body;
    while(t&&e!=t&&n!=t){
        try{
            t=t.parentNode
            }catch(r){
            return false
            }
        }
    return e==t
};

e.$ToLowerCase=function(e){
    if(e)e=e.toLowerCase();
    return e
    };
    
e.$CloneNode=function(e,t){
    return e.cloneNode(t)
    };
    
e.$LoadImage=function(t,n){
    if(e.$IsBrowserOpera()&&i<11.6||!t){
        V(n,null)
        }else{
        var r=new Image;
        r.onload=e.$CreateCallback(null,V,n,r);
        r.onabort=e.$CreateCallback(null,V,n,r,true);
        r.src=t
        }
    };

e.$LoadImages=function(e,t,n){
    function i(e,i){
        r--;
        if(t&&e&&e.src==t.src)t=e;
        !r&&n&&n(t)
        }
        var r=e.length+1;
    $JssorUtils$.$Each(e,function(e){
        $JssorUtils$.$LoadImage(e.src,i)
        });
    i()
    };
    
e.$BuildElement=function(e,t,n,r){
    if(r)e=$JssorUtils$.$CloneNode(e,true);
    var i=$JssorUtils$.$GetElementsByTagName(e,t);
    for(var s=i.length-1;s>-1;s--){
        var o=i[s];
        var u=$JssorUtils$.$CloneNode(n,true);
        $JssorUtils$.$SetClassName(u,$JssorUtils$.$GetClassName(o));
        $JssorUtils$.$SetStyleCssText(u,$JssorUtils$.$GetStyleCssText(o));
        var a=$JssorUtils$.$GetParentNode(o);
        $JssorUtils$.$InsertBefore(a,u,o);
        $JssorUtils$.$RemoveChild(a,o)
        }
        return e
    };
    
var $;
var J=[];
e.$Buttonize=function(e){
    return new K(e)
    };
    
var Q={
    $Opacity:e.$GetStyleOpacity,
    $Top:e.$GetStyleTop,
    $Left:e.$GetStyleLeft,
    $Width:e.$GetStyleWidth,
    $Height:e.$GetStyleHeight,
    $Position:e.$GetStylePosition,
    $Display:e.$GetStyleDisplay,
    $ZIndex:e.$GetStyleZIndex
    };
    
var G={
    $Opacity:e.$SetStyleOpacity,
    $Top:e.$SetStyleTop,
    $Left:e.$SetStyleLeft,
    $Width:e.$SetStyleWidth,
    $Height:e.$SetStyleHeight,
    $Display:e.$SetStyleDisplay,
    $Clip:e.$SetStyleClip,
    $MarginLeft:e.$SetStyleMarginLeft,
    $MarginTop:e.$SetStyleMarginTop,
    $Transform:e.$SetStyleTransform,
    $Position:e.$SetStylePosition,
    $ZIndex:e.$SetStyleZIndex
    };
    
e.$GetStyleSetter=Y;
e.$GetStyleSetterEx=Z;
e.$GetStyles=function(e,t){
    Y();
    var n={};
    
    k(t,function(t,r){
        if(Q[r]){
            n[r]=Q[r](e)
            }
        });
return n
};

e.$SetStyles=function(e,t){
    var n=Y();
    k(t,function(t,r){
        n[r]&&n[r](e,t)
        })
    };
    
e.$SetStylesEx=function(t,n){
    Z();
    e.$SetStyles(t,n)
    };
    
$JssorMatrix$=new function(){
    function t(e,t){
        var n=e[0].length;
        var r=e.length;
        var i=t[0].length;
        var s=[];
        for(var o=0;o<r;o++){
            var u=s[o]=[];
            for(var a=0;a<i;a++){
                var f=0;
                for(var l=0;l<n;l++){
                    f+=e[o][l]*t[l][a]
                    }
                    u[a]=f
                }
            }
            return s
    }
    var e=this;
e.$ScaleX=function(t,n){
    return e.$ScaleXY(t,n,0)
    };
    
e.$ScaleY=function(t,n){
    return e.$ScaleXY(t,0,n)
    };
    
e.$ScaleXY=function(e,n,r){
    return t(e,[[n,0],[0,r]])
    };
    
e.$TransformPoint=function(e,n){
    var r=t(e,[[n.x],[n.y]]);
    return new $JssorPoint$(r[0][0],r[1][0])
    }
};

e.$CreateMatrix=function(e,t,n){
    var r=Math.cos(e);
    var i=Math.sin(e);
    return[[r*t,-i*n],[i*t,r*n]]
    };
    
e.$GetMatrixOffset=function(e,t,n){
    var r=$JssorMatrix$.$TransformPoint(e,new $JssorPoint$(-t/2,-n/2));
    var i=$JssorMatrix$.$TransformPoint(e,new $JssorPoint$(t/2,-n/2));
    var s=$JssorMatrix$.$TransformPoint(e,new $JssorPoint$(t/2,n/2));
    var o=$JssorMatrix$.$TransformPoint(e,new $JssorPoint$(-t/2,n/2));
    return new $JssorPoint$(Math.min(r.x,i.x,s.x,o.x)+t/2,Math.min(r.y,i.y,s.y,o.y)+n/2)
    }
};

$JssorAnimator$=function(e,t,n,r,i,s){
    function L(e,t,n){
        var r=0;
        if(e<t)r=-1;
        else if(e>n)r=1;
        return r
        }
        function A(e){
        return L(e,E,S)
        }
        function O(e){
        return L(e,x,T)
        }
        function M(e){
        x+=e;
        T+=e;
        E+=e;
        S+=e;
        y+=e;
        b+=e;
        $JssorUtils$.$Each(C,function(t){
            t,t.$Shift(e)
            })
        }
        function _(t,n){
        var r=t-x+e*n;
        M(r);
        return T
        }
        function D(e,u){
        var a=e;
        if(N&&(a>=T||a<=x)){
            a=((a-x)%N+N)%N+x
            }
            if(!w||h||u||y!=a){
            var f=Math.min(a,T);
            f=Math.max(f,x);
            if(!w||h||u||f!=b){
                if(s){
                    var l=s;
                    if(i){
                        var c=(f-E)/(t||1);
                        if(n.$Optimize&&$JssorUtils$.$IsBrowserChrome()&&t)c=Math.round(c*t/16)/t*16;
                        if(n.$Reverse)c=1-c;
                        l={};
                        
                        for(var p in s){
                            var g=v[p]||1;
                            var S=m[p]||[0,1];
                            var L=(c-S[0])/S[1];
                            L=Math.min(Math.max(L,0),1);
                            L=L*g;
                            var A=Math.floor(L);
                            if(L!=A)L-=A;
                            var O=d[p]||d.$Default;
                            var M=O(L);
                            var _;
                            var D=i[p];
                            var P=s[p];
                            if($JssorUtils$.$IsNumeric(P)){
                                _=D+(P-D)*M
                                }else{
                                _=$JssorUtils$.$Extend({
                                    $Offset:{}
                                },i[p]);
                            $JssorUtils$.$Each(P.$Offset,function(e,t){
                                var n=e*M;
                                _.$Offset[t]=n;
                                _[t]+=n
                                })
                            }
                            l[p]=_
                        }
                        }
                        if(i.$Zoom){
                l.$Transform={
                    $Rotate:l.$Rotate||0,
                    $Scale:l.$Zoom,
                    $OriginalWidth:n.$OriginalWidth,
                    $OriginalHeight:n.$OriginalHeight
                    }
                }
            if(s.$Clip&&n.$Move){
            var H=l.$Clip.$Offset;
            var B=(H.$Top||0)+(H.$Bottom||0);
            var j=(H.$Left||0)+(H.$Right||0);
            l.$Left=(l.$Left||0)+j;
            l.$Top=(l.$Top||0)+B;
            l.$Clip.$Left-=j;
            l.$Clip.$Right-=j;
            l.$Clip.$Top-=B;
            l.$Clip.$Bottom-=B
            }
            if(l.$Clip&&$JssorUtils$.$CanClearClip()&&!l.$Clip.$Top&&!l.$Clip.$Left&&l.$Clip.$Right==n.$OriginalWidth&&l.$Clip.$Bottom==n.$OriginalHeight)l.$Clip=null;
        $JssorUtils$.$Each(l,function(e,t){
            k[t]&&k[t](r,e)
            })
        }
        o.$OnInnerOffsetChange(b-E,f-E)
    }
    b=f;
$JssorUtils$.$Each(C,function(t,n){
    var r=e<y?C[C.length-n-1]:t;
    r.$GoToPosition(e,u)
    });
var F=y;
var I=e;
y=a;
w=true;
o.$OnPositionChange(F,I)
    }
}
function P(e,t){
    $JssorDebug$.$Execute(function(){
        if(t!==0&&t!==1)$JssorDebug$.$Fail("Argument out of range, the value of 'combineMode' should be either 0 or 1.")
            });
    if(t)e.$Locate(T,1);
    T=Math.max(T,e.$GetPosition_OuterEnd());
    C.push(e)
    }
    function H(){
    if(u){
        var e=$JssorUtils$.$GetNow();
        var t=Math.min(e-p,$JssorUtils$.$IsBrowserOpera()?80:20);
        var r=y+t*c;
        p=e;
        if(r*c>=l*c)r=l;
        D(r);
        if(!h&&r*c>=l*c){
            j(g)
            }else{
            $JssorUtils$.$Delay(H,n.$Interval)
            }
        }
}
function B(e,t,n){
    if(!u){
        u=true;
        h=n;
        g=t;
        e=Math.max(e,x);
        e=Math.min(e,T);
        l=e;
        c=l<y?-1:1;
        o.$OnStart();
        p=$JssorUtils$.$GetNow();
        H()
        }
    }
function j(e){
    if(u){
        h=u=g=false;
        o.$OnStop();
        if(e)e()
            }
        }
e=e||0;
var o=this;
var u;
var a;
var f;
var l;
var c;
var h;
var p=0;
var d;
var v;
var m;
var g;
var y=0;
var b=0;
var w;
var E=e;
var S=e+t;
var x;
var T;
var N;
var C=[];
var k;
o.$Play=function(e,t,n){
    B(e?y+e:T,t,n)
    };
    
o.$PlayToPosition=function(e,t,n){
    B(e,t,n)
    };
    
o.$PlayToBegin=function(e,t){
    B(x,e,t)
    };
    
o.$PlayToEnd=function(e,t){
    B(T,e,t)
    };
    
o.$Stop=function(){
    j()
    };
    
o.$Continue=function(e){
    B(e)
    };
    
o.$GetPosition=function(){
    return y
    };
    
o.$GetPlayToPosition=function(){
    return l
    };
    
o.$GetPosition_Display=function(){
    return b
    };
    
o.$GoToPosition=D;
o.$GoToBegin=function(){
    D(x,true)
    };
    
o.$GoToEnd=function(){
    D(T,true)
    };
    
o.$Move=function(e){
    D(y+e)
    };
    
o.$CombineMode=function(){
    return f
    };
    
o.$GetDuration=function(){
    return t
    };
    
o.$IsPlaying=function(){
    return u
    };
    
o.$IsOnTheWay=function(){
    return y>E&&y<=S
    };
    
o.$SetLoopLength=function(e){
    N=e
    };
    
o.$Locate=_;
o.$Shift=M;
o.$Join=P;
o.$Combine=function(e){
    P(e,0)
    };
    
o.$Chain=function(e){
    P(e,1)
    };
    
o.$GetPosition_InnerBegin=function(){
    return E
    };
    
o.$GetPosition_InnerEnd=function(){
    return S
    };
    
o.$GetPosition_OuterBegin=function(){
    return x
    };
    
o.$GetPosition_OuterEnd=function(){
    return T
    };
    
o.$OnPositionChange=$JssorUtils$.$EmptyFunction;
o.$OnStart=$JssorUtils$.$EmptyFunction;
o.$OnStop=$JssorUtils$.$EmptyFunction;
o.$OnInnerOffsetChange=$JssorUtils$.$EmptyFunction;
o.$Version=$JssorUtils$.$GetNow();
{
    n=$JssorUtils$.$Extend({
        $Interval:15
    },n);
    $JssorDebug$.$Execute(function(){
        n=$JssorUtils$.$Extend({
            $LoopLength:undefined,
            $Setter:undefined,
            $Easing:undefined
        },n)
        });
    N=n.$LoopLength;
    k=$JssorUtils$.$Extend({},$JssorUtils$.$GetStyleSetter(),n.$Setter);
    x=E=e;
    T=S=e+t;
    var v=n.$Round||{};
    
    var m=n.$During||{};
    
    d=$JssorUtils$.$Extend({
        $Default:$JssorUtils$.$IsFunction(n.$Easing)&&n.$Easing||$JssorEasing$.$EaseSwing
        },n.$Easing)
    }
}