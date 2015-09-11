<?php
class DiyTraceBehavior extends Behavior {
    // 
    protected $options   =  array(
        'SHOW_DIY_TRACE' => false,   // 
    );

    //
    public function run(&$params){
        if(C('SHOW_DIY_TRACE')) {
            $this->showTrace();
        }
    }

    private function showTrace(){
        //
        $debug_pic = array (
            'close'=>'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAAA5ElEQVR42tRTQYoEIQwsl/2Bl3gQoY9eBKEf5kvyG8G7h4Z+S38gIu5lp5lZ2R7YPm1BDhZJSFWiGmPgDj5wE7cbfD4/mBkAHprUj9yTTyn9OsGIMSLG+Fxwxc8SiAi9d4QQHskjhIDeO4jorQcq5wwiQmsN3nt479FaAxEh5zxJmyZIKalSClprL1FKQUpJXZr4DBH52xqZeRhjICKw1sJaCxGBMQbMPN41GFpriAicc6i1otYK5xxEBFrraQuThGVZAADbtp2amXms6woAOI7j0gO17/t5MN+HNfEvBf//M30NAKe7aRqUOIlfAAAAAElFTkSuQmCC',
            'config' =>'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAATtJREFUeNqcU0Gqg0AMjZ/eQbEH0IUewIV4BXEjeD9dCvYGUlB056IU3Rf0Ci6c/hc6g/rr7+cHwkySl8xLZkZL05QORKz22jtAkiR0OkigIAjofD7T4/GgsizFLlcV/JLJAKOi4zjsME1zs8KPOHDrw7RXCwLBv0qWZZyLHMmAuq4jIcRHBW4taga3241c1+U9+r5erwrk+76kzri1gIEAII5jdcorWZMKW8aAA17OgRkYhkHLsvza9zoO/OYWQHmeZwYdFZIxKPDrGWhVVTGdKIrY6Xke1XWtrgo26EPyPN+8BTVEy7LU6bquUxiGPxigCHB939P+IZFt2x+vUOL2t8DSNA2D7vc7FUWxSYQNP/bAvXsH2jiO4nK5qMC3za1M08T2MAys+79wOvpxbdt+/I0ckL39V54CDAChFuDJX64gowAAAABJRU5ErkJggg==', 
            'database' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAKlJREFUeNrsk0EOgyAQRT9KiLog7D0Ql+B4HsULeAHXQFwaiCGBCm1Nmi4a69a/IDNk5g+8ZEhKCcMwYFfCORGlFOgrSVJKNE0DxhgofV6HELBtG5xz8N6XuK7rUjOOYx5I3gbQWoNzDiEEuq5DjLE0LcsCYwystVjXFW3bou/74xkVLuqywfGFaZp+T6uqwmGe52+DPyB+GtwQb4h5q3aI6SREko+HAAMADJ+V5b1xqucAAAAASUVORK5CYII=',
            'email' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAAA0ElEQVR42tSTMY7DIBREn1d7C5dINNDQUPgePqXvQUFDYzdIlFwCjJwq1lp2kl2lWGWkXwwfRp8Z/W7bNt7BF2/i/wW+AaZp+rMR4zh2uwBA3/cMw/DyoXOOnPP5C9ZanHOs6/qwnHNYa689qLVijMF7T631VN57jDHUWq8FSimUUlBKEULYeSmFEAJKqZ2fTLxPABBjRErJsiz7pTuXUl6nANBaI6WEEILWGkIIfvaEEMQYD+cHgZQSWuunCWitmef5WiDnfIjnt+g+f5luAwD2dYaKnq3prwAAAABJRU5ErkJggg==', 
            'error' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIrSURBVDjLpdNfSFNhGMdx6Q9RDoIuLAQtYVuWpZSunKQXjYIKwdBqkJkEYRhIBEFRRmVbGYNB2hTnVKaGzWFq/im0mLgyLTNjpdSFJkX/hCxYY26db+ecYliQF3rx44Xz8nzO8748bxgQNp/8d8OoS41s0Ca0uBPXvu3VqMYbk+Parx5Nsl3RRyHmjpjdswKfosOF6ey9CENPEFqdBNM2MaKNJ+D7StflLTIiA8bUrQu8sUuavOrF017lIrwxYqIXErSWwOsR+PgBhgZhoA9XWw0T3UbqTsZLwBEZMKUkhvtUS3uxW6G+GmrEtfsuPH0MXR3gGf79vfIGZQUa3vWYMR+OkYBIGbBpN6r9qxUvZEBsmYMZUHwR6sSiPjf0P4RaG1OnTvidZzS8uV0gFRO6xBaNMiOgXjmB3QY5WZB7AK5dAkc9PBdb7+oUu6pgpLRkymXazlhn4d/AYMIqg2Axf8NQCHnZcCwHTAZodsD4GPTch3vtDJeX88q+n77rOyXAEwK+rFe0in8Iyq1n7oKic9B0C9wugjerf34/lPXDr08PuPJyZKD5fIoEFIUAX2x4v2AthYZaMXaEjlb8Og2TaxTCs317BgMWs/59fm7V5qgIPFWZVOTHSUBaCGhMXmd9GR/hnVQuEz6LGVWt8DuSYh/NnAmxQFd5fIPcwczzzzpI/wDFLRe2zQsYHShLnxcgFz8w7QiN8JwA59lkCTg9F8Dy5xVK6/KZe78AQiW2y4SvvaoAAAAASUVORK5CYII=',
            'info' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIySURBVDjLpdNdTFJhGAdw120X3XXR5kU33fQxS0+5Yl24lFnQKsvl2nJLM0fmXLNASceKgAv8yBGgJPEhkcIShEEYKuKU1IxcTm0WUDiJ1Fpbm1tZ/855186oLS/k4r/34n2e355z9rwZADLSyX8vCm+WU6fqT38+21S4ztPy1rmK4lXF5Ry//Hwm6LjoHN8QOGOgUOe9iGByCJ7FJ5BMX0ORiosfa1/wTHqQIAQ4VCHbwpXL53iWHPAe7QefJAvq4G2MLY9gcnUcQ0kf/AkvAm4DPvhl6Lq+jwEuESD7inLrCWXJ10BygC56SgpHlofxfGUMjvhjDH7sR1e0Hfq3VmiqKSwOt6CldCcD7CDA3qrOXfRo37tjRojC5SRt81KYIxp4lxx0+mCOaqEON8NeR2Ght5ppBvsTT9Yqai60F/y0vTehPlyBW+FKAliiOvQnPGQKY+Q+TOOdCCjzEPU2/A1wxIaH3a8N0C20ouGVAI3TVVC9kcEa0yO0MgrfkptM0mprwqypGKG2AgaYYYEsqfGFI94D4csy1E6VonlWgt64Fb6EG7aYGTdGK1ETEv6yu+wEcDQeZoA7LHBEJfxkiejQQxczccZtEE8JwHNRKLMK1rRzng6R3xU8kLkdM/oidAh2M8BRFsi7W/Iu38wBty8bXCcdSy6OyfjfUneCbjj34OoeMkHq92+4SP8A95wSTlrA/ISGnxZAmgeV+ewKbwqwi3MZQLQZQP3nFTLnttS73y9CuFIqo/imAAAAAElFTkSuQmCC', 
            'log' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAABKElEQVR42sSTPa6CQBSFv3lRrCRYACvQxMLCBhsTK5fAGtiAPaGfDbgGNmBCS0PFAqhsoSGSWE1yrTDvxd+E4t1qkrnn3HPPnFEiwpD6YWCNPjUcj0fquhYAz/MUQBRFzwniOH6cMBrJfD4HoKoq6UleKkiS5H7WWstut+N0OmHbNrZt92rURw+01rJerzmfzyyXSy6XC77vf2ei1lpWqxVN02CMwRhDEARUVcXHFfrJbdsyHo8BcByHsiwxxqjfBj4omE6nstlsuF6vWJaFZVl4nkdZlhwOB/U2B1mWSRiGFEWB67pMJhNc16Uoipfgpx6EYUie58xmM/I8fwsGUH2UsywTgMViAUCapnRdp9498x+COI5lu93eL/b7vfomyurfP9NggtsAfaVzbTWryOIAAAAASUVORK5CYII=', 
            'logo' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAALeSURBVDhPTVTbT9NgFN9fZDLGBgteUBON+GhiJCExvipsnYsKOEM0CrzIA/FGvEQNIkoCCq7tBmFWI3L1gkMciOAkmXIJGdvY2q/tz/O1g/Bw0rVff7dzTucwYWJg5haEcBl8kmdXlaNWLoVfKoFfdKGOfu8+D4geBAe8cMTmuyBILqv8UtlO+WS3BagfOoCAWG4TSW4qz84754jUERQrigQl8MlOUvWSGoFlDijFo8kg7ozVFQW4iIvOuCMP6iQnHH6RbJJFn1QOH5FxJUHkTkoQoEqsjWEk2WcB7XJaDjiBQFgioIcit2vnFfg9vRgIu9GXeABTN5Fcn7bcXJArbQJLgOOIwMpODeEvbPeiXqyEstQF1VBhagwLGwkiduLJ1yY0yUctoEBiXJQI6IZUOThATeqYqEUqvQCDlA1mwDByCM89xPmwF7NrE+j5cr2ozmMXCThji1KD7yvjBDDBTAbd1Ml+FvOr33BRPojn8WZo5hZGlweoV9sRXHD4InvweCoEVc0S2CDbpMyvbAvyXDeEaAV647ehszw9Z/iUiqEhug+CbJM4QpHjyLIMWd5WNTHzbxQ3lFNojVWTKwU69cIwC3TV0Ztox833Nbg7bo/WcfVtFTRi1qlUpuJlvAWNg1VQFjuhUgNNXSXruiWQTC/iUrQSkYVOjP95Q3vgoSbSBJY2psGYjvuTjegYOY2V/CpMQ6M+GEQMAmuYXn6HhqFDaP9wFgWWxnouZRPw8T39HMLIrx6Eho8ho24RuABm9ULHymYSHZMB2r4ympAPmUKOXOnYLKRpMtwB5QhKXjQrJymXQGCdpkDK1HHl5zMEI/txJXoCH3/3E2mBzpkVeebvMO3D3uIiWUvhxuXBI8hrGTC9gO74NQTDh9GfuIWcmrZiGKZKZybWtBRalWq8nr1nL5L9PdgLpSy+wFRSRlvsDH6sjSJP41VZlnJTLE1FrrCOVxNtGE500h+Bif8T6/cL1XlPPAAAAABJRU5ErkJggg==', 
            'memory' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAAAmElEQVR42sSTuwoEIQxFr7JtOgf0n+z9Rhsr/0nB0jKiW00xs7Myj4VNFQj3cAKJGGPgSUk8rNfaeO8vqTjnxAYAAMuynAqXUj4NAKDWen8FKSWstadCIYRjg9YaUkrTsDHm2GAFtNamgP18A2BmKKWmAGaeG+ScpwCt9XdA7x299ylgP//dJQIAEYGI7gNijJcNxN+/8T0A1+E5NmcLfJkAAAAASUVORK5CYII=',
            'reload' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJFSURBVHjaYvz//z8DJQAggJgYKAQAAcSCTTD/YEr2vz9/c//+/af+989fBiC++efP38kLglZNRVcLEECMyF4oPJwq9e/vv42SnFImUtxSDJysXAx///1j+PTjE8P9Nw8YHry8f+bPnz/+qxO2PIPpAQggFC8Abdwox61goiKgysDIyMzw5+8foAF/GZiYmBjUxNUYFEWVTP78+rMRWQ9AAMENyNufnC3BIWkiyyvH8AeoCegShtuvbzPcenGLAWgr2CB5MXkGSSEpE48JjtkwfQABBDcA6M9cCS5Jhu+/vwHxD4a9N/f8u/HsZvONpzead1/Y8+/Tj89gF0mLyjAAXZEL0wcQQPBABAaSOjszB8O//38Z7r25y/Dn99/WZVHr60By/jPcv+49vacDKAZ2DZBWh+kDCCC4AX9/A535/w840P6CFf2B+/P3rz8d2/L2MmKLMYAAgnsBaOrNj98+Mnz99ZVBXEASpKnad5rrMojcHwanDmusKQ4ggJAM+DP5ATCqmIGhzwi0y97AgUlSSDLStcfuv6SIFIOkmBSDVZ0JhiEAAYSSDkLmeZ+WE5U3kROXY/gKDExWJlaGf8DQ/wtUAwqbi1cuMdy7e5/hXM9luHcAAgglHfz+/cf/5sNbZ67cucLw/fsPcKj/Bhrw599vMBsY0CCvorgAIIAYsWUm5y6bbKDCjL+//+qAQl1KUhqYyP4y3AfafmnidZTABAggRmJyo0GRzn9Qnrg86TpGTAAEEAsxOQ4UxZcn38AajQABBgDmDSSSJTnd0QAAAABJRU5ErkJggg==', 
            'sf.png' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo2NEJBNzZDNDMwRDNERjExODA5MUZEQkMxQUVDRTMzRCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozMDIzNDYwOUYzMjYxMUUwQTlEREI4RTA0NTgyNDFFMyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozMDIzNDYwOEYzMjYxMUUwQTlEREI4RTA0NTgyNDFFMyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M0IFdpbmRvd3MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2N0M3MEE3OThDQ0ZFMDExOTQxNENCMkUxNDUyQUUxQSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2NEJBNzZDNDMwRDNERjExODA5MUZEQkMxQUVDRTMzRCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PkNGUsgAAAKDSURBVHjapJRNTBNREMf/b9uuUGihH2sRbIlAFQIEERWj8QO8QEJMjPGu8ebFC0cTPXnxYuLNhIMmagyJiSSmIhdj/AykFIwWtG1AWkgp/dhud7ef+1wOXpS2i87LHN5k5vfmzXszhFKKaiKLaWpQ/OBJCbb6k0QQJWqqM5KdfBloEIZK0DEhEBSwxvP0p6SU960Gy4qb1FCcA1FC0IFDsljCbPofgblMkLL8czCplxBkGxQ044vIwBcrlI3R71izTJzqpVmw4Ql1swLZMQSxZhQ5hWAqkIewlQZO2LQDDYkX0Ptuo1jThHTrGFKNx9Tr6vAkGEPkax424y4yzKc+UDZ0D1LLBSQdw1gmYUhq/Oq6gMnpEkghiT6nVXsN9dIrSK6L4NuuwlOag7+QhZg5gEfTBqwEV9HF6XGmvVE7UNnDQrRfwmTqGZb5BTjJAEKRWng/+tUnT2G0046+/SbtwCx7HD+yS1hYfwdLqRkWpgVv59NQYmsYbGnAaRXIWcxE+7chnfDG3iMe3UJBNIBVV3xLVoubxYDbDleThVRsgr8MjAmylEcsKiMQ+Q45n0FHmwlKox31ltrqXfWnwWi0ELe5F1JKh0+Lc/CtfMb5XhZwd2MpXQSfEemugNvSzw2hZ98hhAIiHs88gKtewJURDtO8Gf5EDrsGtlq7yPWzN9Dvbse3sBczvocYP8JgrKcBnnABixt8+Sy3x1c5nVqcoN03zfToLSud8j6lG8kEfR0U6Pw6T8vFVATG+U16/804PXWXoXc812gl399acdpYzRwZ7riMtuaDiOdW1QOiVaexvpoDV+fE4aZzEHJJ2Boc5L+BOsaAzr2D27XRMtzxS4ABAFymXJw6Mq6eAAAAAElFTkSuQmCC', 
            'time' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAABSElEQVR42pyTwZHCMAxFXxxKUAXuIA3gFnJxERxohAt9QAM5EhpwB6rAJdjJXmKThIWdWc1oJvbo/y8p383tdmMfMcYEtKurLCKHfZ33nsMOmAFjraXrunofQmhVdQYmEVkTvwhijPMaOI5jLXLO0XUdIQSjqrOINBuCGGMu4AL03leC+/1eiQBUNZdOzFJjCth7X8GXy6WSee8Zx7F0WHCYGGOy1la1aZpqrs/zPNcaa21ZNAZoi3rf9+ScawL1O6VE3/frLtrNEgFSSm+/9Hq9AnA+n/ktvhKcTqev5GWEHELAOccwDKSUPuYwDDjnCCEAZAAjIgdV3Sh9yhKqSnFmGWEKIRjnHI/HA4Dj8VgBz+ez+mBRnzY7EJFi1WqWvRMXSxf19m2JItKoalZVY62toDXw61sonSwzJlX98zUCNGuH/Sd+BgBGROvHb4RJ6gAAAABJRU5ErkJggg==',
            'toggle' => 'data:image/gif;base64,R0lGODlhDQAMAPcAAP///5mZmeLi4uXl5f////Pz8wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAAAALAAAAAANAAwAQAgqAAEIHGhgoEGBBhIqLGhwocODAB5CnEhRIUWECSs6ZHhx4kaLByVWhBgQADs=',
            'view' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsTAAALEwEAmpwYAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAAAiElEQVR42szTOQ7DIBBA0Y9lKo5FTTmnpIOaK3ECllSJkINjOTQeCQk06LEMqN47K7GPA+/9laYARGQOHJMDjIi8F1BjbjtObq19NYCcM9ZagH56BIBa63TvKaXrOzgDnHOffozxN1BK+b8KzwDuPqx9VsYlQGu9Bhhj1oAQwi1Arf7GjcV4DQB6u0DjnBIGrgAAAABJRU5ErkJggg==', 
            'warning' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIxSURBVDjLpdNdSFNhGAfww0Cri+gyKLowpMC+LsooEy+SgqJuKqRIiIQKkryoi4zaUmbWSHKdPkYz05xdnKNobmwW6Vi6tbk2TDYl82PTTSr3PXe2s2T+O+dgYwV54S7+vBcvz4/neXleAgCRTf570UXdLda9ORUytW1LDbbkp1TK8h8PLu1rvn92C7houBxfEbA/E+Hn4C6wAQMYTxO8vbkwvMjBYiKED3X7BUQAaFqao6XLgxZyDaxyAp9JArYnBCLjd5CM2bDIupCI6MEEtRjQtWK2rx7t13fzQMUfYHNfx7H4wtQ9xFwPEZuuR+I7jWSgH9H5FrBRI4KeGgTcN6CoKoT3YyMaL+TxwCYBoOi6M5+6i37xgM9YICQ8elnAmKCai4YDJHCPnEDnrUJMdFfxxUg/Ik2JlSPq7anYtAw+0x74zXs54AqYGRLxMN9FK/yem5hySpcMDYfh6hX/DXRR15yhcclS2FEBv+Ugl0OIjFWCmVUgGR9FzE8h6mvGF7MMY21lMJNHecCZBrRUWXhhcrn9ga0IOy4Kxey8BoGZWnwbKsCkbSOGX+cJwFtJEQ9I04C+o5SNTojBuOXc3I8Qn1Nh7v062BUiWHXnWLtD+1TVTxt7anPhfHUayqs7eKAkDajbz3tN5HpYH4swJBfBQq7Fu6aSROZOcAWlLyt3Ch1kzr/iIv0DyHpqirMCvloVJ7MChGJ9w5H0Cq8K6Lx9gAeqVwM8X/6F/Lkh8+43zznRPkqpYfEAAAAASUVORK5CYII=', 
            'plugin'=>'data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsSAAALEgHS3X78AAAABGdBTUEAALGOfPtRkwAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAABmklEQVR42mL4//8/AyUYIIDAxK5du1BwXEb3/9D4FjBOzZ/wH10ehkF6AQIIw4B1G7b+D09o/h+X3gXG4YmteA0ACCCsLghPbPkfm9b5PzK5439Sdg9eAwACCEyANMBwaFwTGIMMAOEQIBuGA6Mb/qMbABBAEAOQnIyMo1M74Tgiqf2/b3gVhgEAAQQmQuKa/8ekdYMxyLCgmEYMHJXc9t87FNMAgACCGgBxIkgzyDaQU5FxQGQN2AUBUXX/vULKwdgjsOQ/SC9AAKEEYlB03f+oFJABdSjYP6L6P0guIqkVjt0DisEGAAQQigEgG0AhHxBVi4L9wqvBBiEHtqs/xACAAAIbEBBd/x+Eg2ObwH4FORmGfYCaQRikCUS7B5YBNReBMUgvQABBDADaAtIIwsEx9f/Dk9pQsH9kHTh8XANKMAIRIIDAhF9ELTiQQH4FaQAZCAsskPNhyRpkK7oBAAEEMSC8GsVGkEaYIlBghcU3gbGzL6YBAAEEJnzCgP6EYs/gcjCGKQI5G4Z9QiswDAAIIAZKszNAgAEAHgFgGSNMTwgAAAAASUVORK5CYII=',            
        );
        $_trace = trace();
       
        $trace_config = array(
            'debug' => 'on',
            'xdebug' => extension_loaded('xdebug') ? 'on' : 'off',
            'HTML_CACHE' => C(HTML_CACHE_ON) ? 'on' : 'off',
            'GD'    =>extension_loaded('gd') && function_exists('gd_info')? 'on' : 'off',
            'tokenizer' => function_exists('token_get_all') ? 'on' : 'off',
            'eaccelerator' => extension_loaded('eaccelerator') && ini_get('eaccelerator.enable') ? 'on' : 'off',
            'apc' => extension_loaded('apc') && ini_get('apc.enabled') ? 'on' : 'off',
            'xcache' => extension_loaded('xcache') && ini_get('xcache.cacher') ? 'on' : 'off',
            'memcache' => extension_loaded('memcache') && ini_get('memcache.default_port') ? 'on' : 'off',
        );
        $trace_config_output = '';
        foreach ($trace_config as $key => $value) {
            $trace_config_output.='<li class="is' . $value . ($key == 'xcache' ? ' last' : '') . '">' . $key . '</li>';
        }
        $grounpname = (C('GROUP_NAME')) ? C('GROUP_NAME') . '/' : '';
        #View
        $view = Think::instance('View');
        $view_var = dump($view->getAllVar(),0);
        $logs = getLogs($_trace);
        $files = get_included_files();
        $file_count = count($files);
        $files = dump_php($files);
        $showtime = showTime();
        #
        $request = dump_php(requestAsArray());
        $response = dump_php(responseAsArray());
        $user = dump_php(userAsArray());
        $settings = dump_php(array_change_key_case(C(), CASE_UPPER));
        $prams = prams($view);
        $glob = dump_php(globalsAsArray());
        $php_info = dump_php(phpInfoAsArray());
        if (empty($_REQUEST)) { 
            $tmpl_var = '<p>No parameters were passed to this template.</p>';
        } else { 
            $tmpl_var = '';
            if(!empty($_GET))
                $tmpl_var.='GET:'.dump_php($_GET);
            if(!empty($_POST))
                $tmpl_var.='<br/>'.dump_php($_POST);
            $tmpl_var = '<p>'.$tmpl_var.'</p>';
        }
        $log_rows ='';
        foreach ($logs as $key => $val) {
        if($val['level'] == ' WARN')
            $log_rows_type=' Warning';
        else if($val['level'] == ' ERR')
            $log_rows_type.=' Error';
        else
            $log_rows_type.=' Info';
        $log_rows.= '<tr class="sfWebDebugLogLine sfWebDebug ';
        $log_rows.= $log_rows_type;
        $log_rows .= $val['level'].'"><td class="sfWebDebugLogNumber">'.($key + 1).'</td><td class="sfWebDebugLogType"><img alt="Info" src="'.$debug_pic[$val['src']].'">&nbsp;<span>'.$val['level'].'</span></td><td>'.$val['log'].'</td></tr>';
        $log_rows.='<tr class="sfWebDebugLogLine sfWebDebug'.$log_rows_type.' ' . $val['level'].'"><td class="sfWebDebugLogNumber">'.($key + 1).'</td><td class="sfWebDebugLogType"><img alt="Info" src="'.$debug_pic[$val['src']].'">&nbsp;<span>'.$val['level'].'</span></td><td>'.$val['log'].'</td></tr>';
        } 
        $showtime_output = '';
        foreach ($showtime['Process_Adv'] as $key => $value) {
            $showtime_output .= '<tr><td class="sfWebDebugLogType">'.$key.'</td><td style="text-align:right">'.$value.'</td><td style="text-align: right">'.round($value / (floatval($showtime['Process'])) * 100, 0).'</td></tr>';
        } 
        $think_version = THINK_VERSION;
        $think_path = THINK_PATH;
        $pageContents = <<< EOPAGE
<style type="text/css">
root{display:block}
dl{margin:0;padding:0}
dt{font-weight:bold;text-align:right;width:11em;clear:both}
dd{margin:-1.35em 0 0 12em;padding-bottom:.4em;overflow:auto}
dd ul li{float:left;display:block;width:16.5%;margin:0;padding:0 0 0 20px;background:url({$debug_pic['plugin']}) 2px 50% no-repeat;line-height:1.6}
#sfWebDebug{color:#333;font-family:Arial,sans-serif;font-size:12px;line-height:12px;margin:0;padding:0;text-align:left}
#sfWebDebug a,#sfWebDebug a:hover{background-color:transparent;border:medium none;color:#000;text-decoration:none}
#sfWebDebug img{border:0 none;display:inline;float:none;margin:0}
#sfWebDebugBar{background-color:#DDD;margin:0;opacity:.8;padding:1px 0;position:absolute;right:0;top:0;white-space:nowrap;z-index:10000}
#sfWebDebugBar[id]{position:fixed}
#sfWebDebugBar img{vertical-align:middle}
#sfWebDebugBar .sfWebDebugMenu{display:inline;margin:0;padding:5px 5px 5px 0}
#sfWebDebugBar .sfWebDebugMenu li{display:inline;list-style:none outside none;margin:0;padding:0 6px}
#sfWebDebugBar .sfWebDebugMenu li.last{border:0 none;margin:0;padding:0}
#sfWebDebugDatabaseDetails li{margin:0 0 0 30px;padding:5px 0}
#sfWebDebugShortMessages li{background-color:#DDD;margin-bottom:10px;padding:5px}
#sfWebDebugShortMessages li{list-style:none outside none}
#sfWebDebugDetails{margin-right:7px}
#sfWebDebug pre{line-height:1.3;margin-bottom:10px}
#sfWebDebug h1{background-color:#EEE;border:0 none;font-size:16px;font-weight:bold;margin:10px 0;padding:0}
#sfWebDebug h2{background:none repeat scroll 0 0 transparent;border:0 none;font-size:14px;font-weight:bold;margin:10px 0;padding:0}
#sfWebDebug h3{background:none repeat scroll 0 0 transparent;border:0 none;font-size:12px;font-weight:bold;margin:10px 0;padding:0}
#sfWebDebug .sfWebDebugTop{background-color:#EFEFEF;border-bottom:1px solid #AAA;left:0;margin:0;padding:0 1%;position:absolute;max-height:70%;opacity:.8;top:0;width:100%;overflow:auto;z-index:9999}
#sfWebDebugLog{font-size:11px;margin:0;padding:3px}
#sfWebDebugLogMenu{margin-bottom:5px}
#sfWebDebugLogMenu li{border-right:1px solid #AAA;display:inline;list-style:none outside none;margin:0;padding:0 5px}
#sfWebDebugConfigSummary{background-color:#DDD;border:1px solid #AAA;display:inline;margin:20px 0;padding:5px}
#sfWebDebugConfigSummary li{display:inline;list-style:none outside none;margin:0;padding:0 5px}
#sfWebDebugConfigSummary li.last{border:0 none}
.sfWebDebugInfo,.sfWebDebugInfo td{background-color:#DDD}
.sfWebDebugWarning,.sfWebDebugWarning td{background-color:orange!important}
.sfWebDebugError,.sfWebDebugError td{background-color:#F99!important}
.sfWebDebugLogNumber{width:1%}
.sfWebDebugLogType{white-space:nowrap;width:1%}
.sfWebDebugLogType,#sfWebDebug .sfWebDebugLogType a{color:darkgreen}
#sfWebDebug .sfWebDebugLogType a:hover{text-decoration:underline}
.sfWebDebugLogInfo{color:blue}
.ison{color:#000;font-weight:bolder;margin-right:5px}
.isoff{color:#F33;margin-right:5px;text-decoration:line-through}
.sfWebDebugLogs{border:1px solid #999;font-family:Arial;font-size:11px;margin:0;padding:0}
.sfWebDebugLogs tr{border:0 none;margin:0;padding:0}
.sfWebDebugLogs td{border:0 none;margin:0;padding:1px 3px;vertical-align:top}
.sfWebDebugLogs th{background-color:#999;border:0 none;color:#EEE;margin:0;padding:3px 5px;vertical-align:top;white-space:nowrap}
.sfWebDebugDebugInfo{border-left:1px solid #AAA;color:#999;font-size:11px;line-height:1.25em;margin:5px 0 5px 10px;padding:2px 0 2px 5px}
.sfWebDebugDebugInfo .sfWebDebugLogInfo,.sfWebDebugDebugInfo a.sfWebDebugFileLink{color:#333!important}
.sfWebDebugCache{font-family:Arial;font-size:9px;margin:0;opacity:.85;overflow:hidden;padding:2px;position:absolute;z-index:995}
#sfWebDebugThinkphpVersion{background-color:#666;color:#FFF;margin-left:0;padding:1px 4px}
#sfWebDebugviewDetails ul{list-style:none outside none;margin:.5em 0;padding-left:2em}
#sfWebDebugviewDetails li{margin-bottom:.5em}
#sfWebDebug .sfWebDebugDataType,#sfWebDebug .sfWebDebugDataType a{color:#666;font-style:italic}
#sfWebDebug .sfWebDebugDataType a:hover{text-decoration:underline}
#sfWebDebugDatabaseLogs{margin-bottom:10px}
#sfWebDebugDatabaseLogs ol{margin:0 0 0 20px;padding:0}
#sfWebDebugDatabaseLogs li{padding:6px}
#sfWebDebugDatabaseLogs li:nth-child(2n+1){background-color:#CCC}
.sfWebDebugDatabaseQuery{margin-bottom:.5em;margin-top:0}
.sfWebDebugDatabaseLogInfo{color:#666;font-size:11px}
.sfWebDebugDatabaseQuery .sfWebDebugLogInfo{color:#909;font-weight:bold}
.sfWebDebugHighlight{background:none repeat scroll 0 0 #FFC}
</style>
<script type="text/javascript">
    /* <![CDATA[ */
    function sfWebDebugGetElementsByClassName(strClass, strTag, objContElm){
        // http://muffinresearch.co.uk/archives/2006/04/29/getelementsbyclassname-deluxe-edition/
        strTag = strTag || "*";
        objContElm = objContElm || document;
        var objColl = (strTag == '*' && document.all) ? document.all : objContElm.getElementsByTagName(strTag);
        var arr = new Array();
        var delim = strClass.indexOf('|') != -1  ? '|' : ' ';
        var arrClass = strClass.split(delim);
        var j = objColl.length;
        for (var i = 0; i < j; i++){
            if(objColl[i].className == undefined) continue;
            var arrObjClass = objColl[i].className.split ? objColl[i].className.split(' ') : [];
            if (delim == ' ' && arrClass.length > arrObjClass.length) continue;
            var c = 0;
            comparisonLoop:
                {
                var l = arrObjClass.length;
                for(var k = 0; k < l; k++){
                    var n = arrClass.length;
                    for(var m = 0; m < n; m++){
                        if(arrClass[m] == arrObjClass[k]) c++;
                        if(( delim == '|' && c == 1) || (delim == ' ' && c == arrClass.length)) {
                            arr.push(objColl[i]);
                            break comparisonLoop;
                        }
                    }
                }
            }
        }
        return arr;
    }
    function sfWebDebugToggleMenu(){
        var element = document.getElementById('sfWebDebugDetails');
        var cacheElements = sfWebDebugGetElementsByClassName('sfWebDebugCache');
        var mainCacheElements = sfWebDebugGetElementsByClassName('sfWebDebugActionCache');
        var panelElements = sfWebDebugGetElementsByClassName('sfWebDebugTop');
        if (element.style.display != 'none'){
            for (var i = 0; i < panelElements.length; ++i){
                panelElements[i].style.display = 'none';
            }
            // hide all cache information
            for(var i = 0; i < cacheElements.length; ++i){
                cacheElements[i].style.display = 'none';
            }
            for(var i = 0; i < mainCacheElements.length; ++i){
                mainCacheElements[i].style.border = 'none';
            }
        }
        else{
            for(var i = 0; i < cacheElements.length; ++i){
                cacheElements[i].style.display = '';
            }
            for(var i = 0; i < mainCacheElements.length; ++i){
                mainCacheElements[i].style.border = '1px solid #f00';
            }
        }
        sfWebDebugToggle('sfWebDebugDetails');
        sfWebDebugToggle('sfWebDebugShowMenu');
        sfWebDebugToggle('sfWebDebugHideMenu');
    }
    function sfWebDebugShowDetailsFor(element){
        if (typeof element == 'string')
            element = document.getElementById(element);
        var panelElements = sfWebDebugGetElementsByClassName('sfWebDebugTop');
        for(var i = 0; i < panelElements.length; ++i){
            if(panelElements[i] != element){
                panelElements[i].style.display = 'none';
            }
        }
        sfWebDebugToggle(element);
    }
    function sfWebDebugToggle(element){
        if (typeof element == 'string')
            element = document.getElementById(element);
        if (element)
            element.style.display = element.style.display == 'none' ? '' : 'none';
    }
    function sfWebDebugToggleMessages(klass){
        var elements = sfWebDebugGetElementsByClassName(klass);
        var x = elements.length;
        for (var i = 0; i < x; ++i){
            sfWebDebugToggle(elements[i]);
        }
    }
    function sfWebDebugToggleAllLogLines(show, klass){
        var elements = sfWebDebugGetElementsByClassName(klass);
        var x = elements.length;
        for (var i = 0; i < x; ++i){
            elements[i].style.display = show ? '' : 'none';
        }
    }
    function sfWebDebugShowOnlyLogLines(type){
        var types = new Array();
        types[0] = 'info';
        types[1] = 'warning';
        types[2] = 'error';
        for (klass in types){
            var elements = sfWebDebugGetElementsByClassName('sfWebDebug' + types[klass].substring(0, 1).toUpperCase() + types[klass].substring(1, types[klass].length));
            var x = elements.length;
            for (var i = 0; i < x; ++i){
                if ('tr' == elements[i].tagName.toLowerCase()){
                    elements[i].style.display = (type == types[klass]) ? '' : 'none';
                }
            }
        }
    }
    /* ]]> */
</script>
<div id="sfWebDebug">
    <div id="sfWebDebugBar">
        <a onclick="sfWebDebugToggleMenu(); return false;" href="javascript:void(0);"><img alt="Debug toolbar" src="{$debug_pic['logo']}"></a>
        <ul class="sfWebDebugMenu" id="sfWebDebugDetails">
            <li><span id="sfWebDebugThinkphpVersion">{$think_version}</span></li>
            <li class="sfWebDebugInfo"><a onclick="sfWebDebugShowDetailsFor('sfWebDebugconfigDetails'); return false;" href="javascript:void(0);" title="Configuration"><img alt="Config" src="{$debug_pic['config']}"> config</a></li>
            <li class="sfWebDebugInfo"><a onclick="sfWebDebugShowDetailsFor('sfWebDebugviewDetails'); return false;" href="javascript:void(0);" title="View Layer"><img alt="View Layer" src="{$debug_pic['view']}"> view</a></li>
            <li class="sfWebDebugInfo"><a onclick="sfWebDebugShowDetailsFor('sfWebDebuglogsDetails'); return false;" href="javascript:void(0);" title="Logs"><img alt="Log" src="{$debug_pic['log']}"> logs</a></li>
            <li><img alt="Memory" src="{$debug_pic['memory']}">{$showtime['UseMem']}</li>
            <li class="sfWebDebugInfo"><a onclick="sfWebDebugShowDetailsFor('sfWebDebugtimeDetails'); return false;" href="javascript:void(0);" title="Timers"><img alt="Time" src="{$debug_pic['time']}">{$showtime['Process']}</a></li>
            <li class="last">
                <a onclick="document.getElementById('sfWebDebug').style.display='none'; return false;" href="javascript:void(0);"><img alt="Close" src="{$debug_pic['close']}"></a>
            </li>
        </ul>
    </div>
    <div style="display:none" class="sfWebDebugTop" id="sfWebDebugconfigDetails">
        <h1>Configuration</h1>
        <ul id="sfWebDebugConfigSummary">
            {$trace_config_output}
        </ul>
        <h2>Request <a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugRequest'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h2>
        <div style="display: none" id="sfWebDebugRequest">
            <p>{$request}</p>
        </div>

        <h2>Response <a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugResponse'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h2>
        <div style="display: none" id="sfWebDebugResponse">
            <p>{$response}</p>
        </div>

        <h2>User <a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugUser'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h2>
        <div style="display: none" id="sfWebDebugUser">
            <p>{$user}</p>
        </div>

        <h2>Settings <a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugSettings'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h2>
        <div style="display: none" id="sfWebDebugSettings">
            <p>{$settings}</p>
        </div>

        <h2>Prams<a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugPrams'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h2>
        <div style="display: none" id="sfWebDebugPrams">
            <p>{$prams}</p>
        </div>

        <h2>Globals <a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugGlobals'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h2>
        <div style="display: none" id="sfWebDebugGlobals">
            <p>{$glob}</p>
        </div>
        <h2>PHP <a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugPhp'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h2>
        <div style="display: none" id="sfWebDebugPhp">
            <p>{$php_info}</p>
        </div>
        <h2>Thinkphp <a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugSymfony'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h2>
        <div style="display: none" id="sfWebDebugSymfony">
            <pre>version: {$think_version}  path: {$think_path}</pre>
        </div>
    </div>
    <div style="display:none" class="sfWebDebugTop" id="sfWebDebugviewDetails">
        <h1>View Layer</h1>
        <h2>Template: <span title="{$_trace['template_file']}">{$_trace['template_file']}</span><a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugViewTemplate1'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h2>
        <div style="display:block" id="sfWebDebugViewTemplate1">{$tmpl_var}</div>
        <h1>passed values:<a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugViewTemplate2'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h1>
        <pre id="sfWebDebugViewTemplate2">{$view_var}</pre>
        <h1>loaded Files:<a title="Toggle details" onclick="sfWebDebugToggle('sfWebDebugViewTemplate3'); return false;" href="javascript:void(0);"><img alt="Toggle details" src="{$debug_pic['toggle']}"></a></h1>
        <pre id="sfWebDebugViewTemplate3">{$file_count}ä¸ªæ–‡ä»?br />{$files}</pre>
    </div>
    <div style="display:none" class="sfWebDebugTop" id="sfWebDebuglogsDetails"><h1>Logs</h1>
        {$showtime['DB']}{$showtime['Cache']}
        <ul id="sfWebDebugLogMenu">
            <li><a onclick="sfWebDebugToggleAllLogLines(true, 'sfWebDebugLogLine'); return false;" href="javascript:void(0);">[all]</a></li>
            <li><a onclick="sfWebDebugToggleAllLogLines(false, 'sfWebDebugLogLine'); return false;" href="javascript:void(0);">[none]</a></li>
            <li><a onclick="sfWebDebugShowOnlyLogLines('info'); return false;" href="javascript:void(0);"><img alt="Show only infos" src="{$debug_pic['info']}"></a></li>
            <li>
                <a onclick="sfWebDebugShowOnlyLogLines('warning'); return false;" href="javascript:void(0);"><img alt="Show only warnings" src="{$debug_pic['warning']}"></a>
            </li>
            <li><a onclick="sfWebDebugShowOnlyLogLines('error'); return false;" href="javascript:void(0);"><img alt="Show only errors" src="{$debug_pic['error']}"></a></li>
            <li><a onclick="sfWebDebugToggleMessages('ALERT'); return false;" href="javascript:void(0);">ALERT</a></li>
            <li><a onclick="sfWebDebugToggleMessages('CRIT'); return false;" href="javascript:void(0);">CRIT</a></li>
            <li><a onclick="sfWebDebugToggleMessages('DEBUG'); return false;" href="javascript:void(0);">DEBUG</a></li>
            <li><a onclick="sfWebDebugToggleMessages('EMERG'); return false;" href="javascript:void(0);">EMERG</a></li>
            <li><a onclick="sfWebDebugToggleMessages('ERR'); return false;" href="javascript:void(0);">ERR</a></li>
            <li><a onclick="sfWebDebugToggleMessages('INFO'); return false;" href="javascript:void(0);">INFO</a></li>
            <li><a onclick="sfWebDebugToggleMessages('NOTIC'); return false;" href="javascript:void(0);">NOTIC</a></li>
            <li><a onclick="sfWebDebugToggleMessages('SQL'); return false;" href="javascript:void(0);">SQL</a></li>
            <li><a onclick="sfWebDebugToggleMessages('WARN'); return false;" href="javascript:void(0);">WARN</a></li>
        </ul>
        <div id="sfWebDebugLogLines">
            <table class="sfWebDebugLogs">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>type</th>
                        <th>message</th>
                    </tr>
                    {$log_rows}
                </tbody>
            </table>
        </div>
    </div>
    <div style="display:none" class="sfWebDebugTop" id="sfWebDebugtimeDetails">
        <h1>Timers</h1>
        <table style="width:300px" class="sfWebDebugLogs">
            <tbody>
                <tr><th>type</th><th>time</th><th>time(%)</th></tr>
                {$showtime_output}
            </tbody>
        </table>
    </div>
</div>
EOPAGE;
        if(C('TMPL_TRACE_FILE'))
            include C('TMPL_TRACE_FILE');
        else
            echo $pageContents;
    // 
    }
   
}
    #^_^
    function showTime() {
        //
        G('beginTime', $GLOBALS['_beginTime']);
        G('viewEndTime');
        $showTime ['Process'] = G('beginTime', 'viewEndTime') . 's ';
        //
        if (C('SHOW_ADV_TIME')) {
            //
            $showTime['Process_Adv']['Load'] = G('beginTime', 'loadTime') . 's';
            $showTime['Process_Adv']['Init'] = G('loadTime', 'initTime') . 's';
            $showTime['Process_Adv']['Exec'] = G('initTime', 'viewStartTime') . 's';
            $showTime['Process_Adv']['Template'] = G('viewStartTime', 'viewEndTime') . 's';
        }
        //
        if (class_exists('Db', false)) {
            $showTime['DB'] = N('db_query') . ' queries ' . N('db_write') . ' writes ';
        }
        //
        if (class_exists('Cache', false)) {
            $showTime['Cache'] = N('cache_read') . ' gets ' . N('cache_write') . ' writes ';
        }
        //
        if (MEMORY_LIMIT_ON) {
            $showTime['UseMem'] = number_format((memory_get_usage() - $GLOBALS['_startUseMems']) / 1024) . ' kb';
        }

        // 
        $showTime ['LoadFile'] = count(get_included_files());
        //
        $fun = get_defined_functions();
        $showTime ['CallFun'] = count($fun['user']) . ',' . count($fun['internal']);
        return $showTime;
    }
    
    #
    function templateContentReplace($content) {
        //
        $replace = array(
            '__ROOT__'   => __ROOT__, // 
            '__APP__'    => __APP__, // 
            '__GROUP__'  =>   defined('GROUP_NAME')?__GROUP__:__APP__,
            '__UPLOAD__' => __ROOT__ . '/Uploads',
            '__ACTION__' => __ACTION__, //
            '__SELF__'   => __SELF__, //
            '__URL__'    => __URL__,
            '../Public'  => APP_TMPL_PATH.'Public',// 
            '__PUBLIC__' => __ROOT__.'/Public',// 
        );
        // 
        if (is_array(C('TMPL_PARSE_STRING'))) $replace = array_merge($replace, array_change_key_case(C('TMPL_PARSE_STRING'), CASE_UPPER));
        $content = str_replace(array_keys($replace), array_values($replace), $content);
        return $content;
    }
    
    #
    function prams($view) {
        $result = array(
            '../Public' => '../Public',
            '__PUBLIC__' => '__PUBLIC__',
            '__GROUP__'   =>'__GROUP__',
            '__ROOT__' => '__ROOT__',
            '__APP__' => '__APP__',
            '__URL__' => '__URL__',
            '__ACTION__' => '__ACTION__',
            '__SELF__' => '__SELF__',
            '__UPLOAD__' => '__UPLOAD__',
        );
        $return = '';
        foreach ($result as $key => $value) {
            $return.= $key . '&nbsp;&nbsp;:&nbsp;&nbsp;' . templateContentReplace($value) . '<br/>';
        }
        return $return;
    }
    
    #
    function requestAsArray() {
        return array(
            'GET' => $_GET,
            'POST' => $_POST,
            'FILES' => $_FILES,
            '模块/方法' => MODULE_NAME . '/' . ACTION_NAME,
            'Action文件路径' => APP_PATH . '/lib/' . $grounpname . 'Action/' . MODULE_NAME . 'Action.class.php'
        );
    }
    
    #
    function responseAsArray() {
         return array(
            '服务器'      => $_SERVER['SERVER_SOFTWARE'],
            '域名'        => $_SERVER['SERVER_NAME'],
            '服务器IP'    => $_SERVER['SERVER_ADDR'],
            '服务器端口'  => $_SERVER['SERVER_PORT'],
            '通信协议'    => $_SERVER['SERVER_PROTOCO'],
            'Content-Type'=> $_SERVER['HTTP_ACCEPT_CHARSET'],
            '请求方法'    => $_SERVER['REQUEST_METHOD'],
            '浏览器'      => $_SERVER['HTTP_USER_AGENT'],
            '会话ID'      => $_trace['会话ID'],
        );
    }
    #
    function userAsArray($trace_user) {
        global $trace_user;
        if (isset($trace_user))
            return $trace_user;
        else
            return 'no  user defined vars';
    }
    
    function globalsAsArray() {
        $values = array();
        foreach (array('cookie', 'server', 'get', 'post', 'files', 'env', 'session') as $name) {
            if (!isset($GLOBALS['_' . strtoupper($name)])) {
                continue;
            }
            $values[$name] = array();
            foreach ($GLOBALS['_' . strtoupper($name)] as $key => $value) {
                $values[$name][$key] = $value;
            }
            ksort($values[$name]);
        }
        ksort($values);
        return $values;
    }

    function phpInfoAsArray() {
        $values = array(
            'php' => phpversion(),
            'os' => php_uname(),
            'extensions' => get_loaded_extensions(),
        );
        natcasesort($values['extensions']);
        $array = array();
        foreach ($values['extensions'] as $key)
            $array[] = $key;
        $values['extensions'] = $array;
        // assign extension version
        if ($values['extensions']) {
            foreach ($values['extensions'] as $key => $extension) {
                $phpExtContents .= "<li>${extension}</li>";
            }
            $values['extensions']  = '
            <dl class="content">
                <dt>php ext</dt> 
                <dd>
                    <ul>'
                    .$phpExtContents.
                    '</ul>
                </dd>
            </dl>';
        }
        return $values;
    }

    
    function dump_php($values) {
        $return = '';
        if (is_array) {
            foreach ($values as $key => $value) {
                if (is_string($value))
                    $return.= $key . ':'. $value .'<br/>';
                if (is_array($value)) {
                    $return.= $key . ':<br/>';
                    foreach ($value as $name => $val) {
                        $return.= str_repeat('&nbsp;', 4). $name . ':';
                        if (is_string($val))
                            $return.= $val . '<br/>';
                        if (is_array($val)) {
                            $return.= '<br/>';
                            foreach ($val as $k => $v) {
                                $return.= str_repeat('&nbsp;', 8) . $k . ':' . $v . '<br/>';
                            }
                        }
                    }
                }
            }
        } else {
            $return = dump($values,0);
        }
        return $return;
    }
    
    #
    function getLogs($_trace) {
        $logs = $_trace['日志记录'];
        $logs = explode('<br/>', $logs);
        unset($logs[0]);//
        if (is_array($logs)) {
            foreach ($logs as $key => $log)
                $tlog[] = substr($log, 29);
            $ttlog = array();
            foreach ($tlog as $tl) {
                $tl = explode(':', $tl);
                $tl[0] = explode('|', $tl[0]);
                $tl[0] = trim($tl[0][1]);
                $src = 'info';
                if ($tl[0] == ' WARN')
                    $src = 'warning';
                if ($tl[0] == ' ERR')
                    $src = 'error';
                $ttlog[] = array('level' => $tl[0], 'log' => $tl[1] . $tl[2], 'src' => $src);
            }
        }
        return $ttlog;
    }