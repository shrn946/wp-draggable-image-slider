<div class="lod">
    <link rel="stylesheet" type="text/css" href="<?php echo DRAG_SLIDER_URL; ?>css/base.css" />
    <script>
        document.documentElement.className = "js";
        var supportsCssVars = function () {
            var e, t = document.createElement("style");
            t.innerHTML = "root: { --tmp-var: bold; }";
            document.head.appendChild(t);
            e = !!(window.CSS && window.CSS.supports && window.CSS.supports("font-weight", "var(--tmp-var)"));
            t.parentNode.removeChild(t);
            return e;
        };
        supportsCssVars() || alert("Please view this demo in a modern browser that supports CSS Variables.");
    </script>

    <main>
        <div class="frame"><div class="frame__indicator"></div></div>
        <div class="strip-outer">
            <div class="strip-inner">
                <div class="draggable"></div>
                <div class="strip">
                    <?php
                    $images = get_option('drag_slider_images', []);
                    $size_classes = ['img-outer--size-s', 'img-outer--size-m', 'img-outer--size-l', 'img-outer--size-xl'];
                    $count = 1;

                    if (!empty($images)) :
                        foreach ($images as $img_url) :
                            $random_class = $size_classes[array_rand($size_classes)];
                            ?>
                            <div class="strip__item">
                                <div class="img-outer <?php echo esc_attr($random_class); ?>">
                                    <div class="img-inner" style="background-image:url('<?php echo esc_url($img_url); ?>');"></div>
                                </div>
                                <span class="strip__item-number">
                                    <a class="strip__item-link"><span></span></a>
                                    <span class="strip__item-plus"></span>
                                </span>
                            </div>
                        <?php endforeach;
                    else : ?>
                        <p style="padding: 20px; color: red;">No images found. Please upload images from <strong>Settings > Drag Slider</strong>.</p>
                    <?php endif; ?>
                </div><!--/strip-->
            </div><!--/strip-inner-->
            <div class="strip-cover"></div>
        </div><!--/strip-outer-->
    </main>

    <div class="cursor">
        <div class="cursor__inner cursor__inner--circle">
            <div class="cursor__side cursor__side--left"></div>
            <div class="cursor__side cursor__side--right"></div>
        </div>
    </div>

    <script src="<?php echo DRAG_SLIDER_URL; ?>js/imagesloaded.pkgd.min.js"></script>
    <script src="<?php echo DRAG_SLIDER_URL; ?>js/TweenMax.min.js"></script>
    <script src="<?php echo DRAG_SLIDER_URL; ?>js/draggabilly.pkgd.min.js"></script>
    <script src="<?php echo DRAG_SLIDER_URL; ?>js/demo.js"></script>
    <script>
        imagesLoaded(document.querySelectorAll('.img-inner'), { background: true }, () => document.body.classList.remove('loading'));
    </script>
</div>
