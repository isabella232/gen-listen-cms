<%-- Simplified music header  --%>

<header class="container">
    <div class="row nav-header">
        <nav class="social">
            <ul>
                <li><span class="">FOLLOW</span></li>
                <li><a href="" target="_blank"><img src="{$ThemeDir}/images/nav_twitterBtn.png"></a></li>
                <li><a href="" target="_blank"><img src="{$ThemeDir}/images/nav_fbBtn.png"></a></li>
                <li><a href="" target="_blank"><img src="{$ThemeDir}/images/nav_tumblrBtn.png"></a></li>
            </ul>
        </nav>

    </div>

    <div class="row share-header">
        <div class="share-bar">
            <input type="hidden" id="facebook-share-text" value="{$FacebookShareText}" />
            <input type="hidden" id="twitter-share-text" value="{$TwitterShareText}" />
            <span class="share-text"><span class="share-hashtag">#GENLISTEN</span></span>
            <span class="share-twitter"><a href="javascript:;"><img src="{$ThemeDir}/images/nav2_tweet.png"></a></span>
            <span class="share-fb"><a href="javascript:;"><img src="{$ThemeDir}/images/nav2_share.png"></a></span>
            <%-- <span class="share-text header-subscription-container">
            <form class="newsletter-signup-form" formmethod="POST" formaction="{$SiteConfig.NewsletterSubmitUrl}">
                <input type="text"><button class="newsletter-signup-button link-button">Submit</button>
            </form>
            </span> --%>

        </div>
    </div>

</header>