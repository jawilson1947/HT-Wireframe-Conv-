FSR.surveydefs = [{
    name: 'browse',
    section: 'knowledge',
    invite: {
        when: 'onentry',
        dialogs: [[{
            reverseButtons: false,
            headline: "We'd welcome your feedback!",
            blurb: "Thank you for visiting Autodesk Knowledge Network. You have been selected to participate in a brief customer satisfaction survey to let us know how we can improve your experience.",
            noticeAboutSurvey: "The survey is designed to measure your entire experience, please look for it at the <u>conclusion</u> of your visit.",
            attribution: "This survey is conducted by an independent company ForeSee, on behalf of the site you are visiting.",
            closeInviteButtonText: "Click to close.",
            declineButton: "No, thanks",
            acceptButton: "Yes, I'll give feedback",
            error: "Error",
            warnLaunch: "this will launch a new window"
        
        }]]
    },
    pop: {
        when: 'later'
    },
    criteria: {
        sp: 70,
        lf: 2
    },
    include: {
        urls: ['knowledge.autodesk.com']
    }
}, {
    name: 'browse',
	section: 'student',
    invite: {
        when: 'onentry'
    },
    pop: {
        when: 'later'
    },
    criteria: {
        sp: 33,
        lf: 2
    },
    include: {
        urls: ['students.autodesk.com', 'www.autodesk.com/education']
    }
}, {
    name: 'browse',
    invite: {
        when: 'onentry'
    },
    pop: {
        when: 'later'
    },
    criteria: {
        sp: 100,
        lf: 2
    },
    include: {
        urls: ['.']
    }
}];
FSR.properties = {
    repeatdays : 90,

    repeatoverride : false,

    altcookie : {
    },

    language : {
        locale : 'en'
    },

    exclude : {
    },

    zIndexPopup : 10000,

    ignoreWindowTopCheck : false,

    ipexclude : 'fsr$ip',

    mobileHeartbeat : {
        delay : 60, /*mobile on exit heartbeat delay seconds*/
        max : 3600  /*mobile on exit heartbeat max run time seconds*/
    },

    invite : {

        // For no site logo, comment this line:
        siteLogo : "sitelogo.gif",

        // For using locale logo, comment above line and include below line:
        //siteLogo : {base:"sitelogo.gif", loc:"sitelogoloc.gif"},
        /* Desktop */
        dialogs : [[{
            reverseButtons: false,
            headline: "We'd welcome your feedback!",
            blurb: "Thank you for visiting our website. You have been selected to participate in a brief customer satisfaction survey to let us know how we can improve your experience.",
            noticeAboutSurvey: "The survey is designed to measure your entire experience, please look for it at the <u>conclusion</u> of your visit.",
            attribution: "This survey is conducted by an independent company ForeSee, on behalf of the site you are visiting.",
            closeInviteButtonText: "Click to close.",
            declineButton: "No, thanks",
            acceptButton: "Yes, I'll give feedback",
            error: "Error",
            warnLaunch: "this will launch a new window"

        }]],

        exclude : {
            urls:['/diamond-club/welcome', '/platinum-club/welcome', /https/, /shop\.autodesk\.com/, /store\.autodesk\.com/, /mexico\.autodesk\.com/, /latinoamerica\.autodesk\.com/, /asean\.autodesk\.com/, /saarc\.autodesk\.com/, /south-apac\.autodesk\.com/, /romania\.autodesk\.com/, /greece\.autodesk\.com/, /south-east-europe\.autodesk\.com/, /turkey\.autodesk\.com/, /\.ca/, /\.br/, /com\.au/, /\.cn/, /\.hk/, /\.in/, /\.jp/, /\.kr/, /\.my/, /\.nz/, /\.sg/, /\.tw/, /\.de/, /\.cz/, /\.hu/, /\.pl/, /\.ru/, /\.za/, /\.dk/, /\.fi/, /\.nl/, /\.no/, /\.se/, /\.uk/, /\.es/, /\.fr/, /\.it/, /\.pt/, /\.tr/, /\.mx/],
            referrers:[],
            userAgents:[],
            browsers:[],
            cookies:[],
            variables:[]
        },
        include : {
            local : [ '.' ]
        },

        delay : 0,
        timeout : 0,

        hideOnClick : false,

        hideCloseButton : false,

        css : 'foresee-dhtml.css',

        hide : [],

        hideFlash: false,

        type : 'dhtml',
        /* desktop */
        // url: 'invite.html'
        /* mobile */
        url : 'invite-mobile.html',
        back: 'url'

        //SurveyMutex: 'SurveyMutex'
    },

    tracker : {
        width : '690',
        height : '415',
        timeout : 4,
        adjust : false,
        alert : {
            enabled : true,
            message : 'The survey is now available.'
        },
        url : 'tracker.html'
    },

    survey : {
        width : 690,
        height : 600
    },

    qualifier : {
        footer : '<div id=\"fsrcontainer\"><div style=\"float:left;width:80%;font-size:8pt;text-align:left;line-height:12px;\">This survey is conducted by an independent company ForeSee,<br>on behalf of the site you are visiting.</div><div style=\"float:right;font-size:8pt;\"><a target="_blank" title="Validate TRUSTe privacy certification" href="//privacy-policy.truste.com/click-with-confidence/ctv/en/www.foreseeresults.com/seal_m"><img border=\"0\" src=\"{%baseHref%}truste.png\" alt=\"Validate TRUSTe Privacy Certification\"></a></div></div>',
        width : '690',
        height : '500',
        bgcolor : '#333',
        opacity : 0.7,
        x : 'center',
        y : 'center',
        delay : 0,
        buttons : {
            accept : 'Continue'
        },
        hideOnClick : false,
        css : 'foresee-dhtml.css',
        url : 'qualifying.html'
    },

    cancel : {
        url : 'cancel.html',
        width : '690',
        height : '400'
    },

    pop : {
        what : 'survey',
        after : 'leaving-site',
        pu : false,
        tracker : true
    },

    meta : {
        referrer : true,
        terms : true,
        ref_url : true,
        url : true,
        url_params : false,
        user_agent : false,
        entry : false,
        entry_params : false
    },

    events : {
        enabled : true,
        id : true,
        codes : {
            purchase : 800,
            items : 801,
            dollars : 802,
            followup : 803,
            information : 804,
            content : 805
        },
        pd : 7,
        custom : {}
    },

    previous : false,

	analytics : {
		google_local : false,
		google_remote : false
	},

cpps: {
        nav_main: {
            source: 'url',
            init: 'N',
            patterns: [{
              //regex: 'autodesk.com',
			    regex: 'www.autodesk.com(?!((.)*education(.)*))',
                value: 'Y'
            }, {
                regex: 'usa.autodesk.com',
                value: 'Y'
            }]
        },
        nav_student: {
            source: 'url',
            init: 'N',
            patterns: [{
                regex: 'students.autodesk.com',
                value: 'Y'
            }, {
                regex: 'www.autodesk.com/education',
                value: 'Y'
            }]
        },
        nav_au: {
            source: 'url',
            init: 'N',
            patterns: [{
                regex: 'au.autodesk.com',
                value: 'Y'
            }]
        },
        nav_forums: {
            source: 'url',
            init: 'N',
            patterns: [{
                regex: 'forums.autodesk.com',
                value: 'Y'
            }]
        },
		nav_knowledge: {
            source: 'url',
            init: 'N',
            patterns: [{
                regex: 'knowledge.autodesk.com',
                value: 'Y'
            }]
        },
        eVar31: {
            init: 'N',
            source: 'variable',
            name: 'eVar31'
        },
        eVar47: {
            init: 'N',
            source: 'variable',
            name: 'eVar47'
        },
        event2: {
            init: 'N',
            source: 'variable',
            name: 'event2'
        },
        event8: {
            init: 'N',
            source: 'variable',
            name: 'event8'
        },
        event28: {
            init: 'N',
            source: 'variable',
            name: 'event28'
        },
        event37: {
            init: 'N',
            source: 'variable',
            name: 'event37'
        },
        channel: {
            init: 'N',
            source: 'variable',
            name: 'channel'
        },
        products: {
            init: 'N',
            source: 'variable',
            name: 'products'
        }
    },

    mode : 'first-party'
};