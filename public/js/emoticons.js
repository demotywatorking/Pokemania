/** 
  *************************************
  Emoticons 
  *************************************
*/

  function seviEmoticon(path)
  {
    if (path==undefined)
      path="public/img/emoticons";
    String.prototype.replaceArray = function(find, replace) 
    {
      var replaceString = this;
      for (var i = 0; i < find.length; i++) {
        replaceString = replaceString.replace(find[i], replace[i]);
      }
      return replaceString;
    };
    String.prototype.makeEmo = function()
    {
        refreshNames();
        return this.replaceArray(find, replace);
    };
    function refreshNames()
    {
      happy = '<img class="emoImage" src="'+path+'/emoHappy.png" alt="emoHappy" />';
      angry = '<img class="emoImage" src="'+path+'/emoAngry.png" alt="emoAngry" />';
      lol = '<img class="emoImage" src="'+path+'/emoLol.png" alt="emoLol" />';
      love = '<img class="emoImage" src="'+path+'/emoLove.png" alt="emoLove" />';
      kiss = '<img class="emoImage" src="'+path+'/emoKiss.png" alt="emoKiss" />';
      wink = '<img class="emoImage" src="'+path+'/emoWink.png" alt="emoWink" />';
      tongue = '<img class="emoImage" src="'+path+'/emoTongue.png" alt="emoTongue" />';
      surprised = '<img class="emoImage" src="'+path+'/emoSurprised.png" alt="emoSurprised" />';
      sleep = '<img class="emoImage" src="'+path+'/emoSleep.png" alt="emoSleep" />';
      sad = '<img class="emoImage" src="'+path+'/emoSad.png" alt="emoSad" />';
      priv = '<img class="emoImage" src="'+path+'/emoPrivate.png" alt="emoPrivate" />';
      cry = '<img class="emoImage" src="'+path+'/emoCry.png" alt="emoCry" />';
      angel = '<img class="emoImage" src="'+path+'/emoAngel.png" alt="emoAngel" />';
      evil = '<img class="emoImage" src="'+path+'/emoEvil.png" alt="emoEvil" />';
      cool = '<img class="emoImage" src="'+path+'/emoCool.png" alt="emoCool" />';
      emoQuest = '<img class="emoImage" src="'+path+'/emoQuest.png" alt="emoQuest" />';
      replace =  [love, cool,angry,angry,emoQuest,angel,angel,angel,angel,angel,angel,happy, happy, happy, happy, happy, happy, happy, happy, happy,happy, happy, happy,lol, lol, lol, lol, lol, lol, lol, lol, lol, lol,lol, lol, lol, lol,lol,wink, wink, wink, wink, wink, wink, wink, wink, wink,tongue,tongue,tongue,tongue,tongue,tongue,tongue,tongue,tongue,tongue,tongue,surprised,surprised,surprised,surprised,surprised,surprised,surprised,sad,sad,sad,sad,sad,sad,sad,sad,sad,sleep,sleep,sleep,sleep,sleep,sleep,sleep,sleep,sleep,priv,priv,priv,priv,priv,priv,priv,priv,priv,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,cry,love,kiss,kiss];
    }
    var replace,happy,angry,lol,love,kiss,wink,tongue,surprised,sleep,sad,priv,cry,angel,evil,cool,emoQuest;
    var find = ["\\&lt\\;3", "\\|\\;\\-\\)","\\:\\-\\|\\|", "\\:@","\\=\\?","0\\:\\-\\)", "0\\:\\-3","0\\;3","0\\:\\-\\)","0\\:\\)","0\\;\\^\\)","\\:\\)", "\\:>", "\\:\\]", "\\;3","\\:\\}","\\=\\)","\\=\\]","8\\)", "\\:\\-\\)", "\\:o\\)","\\:c\\)","\\:\\^\\)","\\:D","\\:d","\\;d", "xD", "XD","8D","\\=D","\\=3","\\:\\-D","8\\-D", "x\\-D","X\\-D","\\=\\-D","\\=\\-3","B\\^D","\\;\\)", "\\;\\]", "\\;D", "\\*\\)","\\*\\-\\)", "\\;\\-\\)", "\\;\\-\\]", "\\;\\^\\)","\\:\\-\\,","\\:p", "\\=p", "\\:b","\\:P", "xp","XP","x\\-p","X\\-P","\\:\\-p","\\:\\-b","\\:\\-P", "\\:O", "\\:o",":\\-O","o_O", "o_0", "o\\.O","8\\-0","\\:\\(",  "\\:\\-\\(", "\\:\\-c", "\\:c","\\:\\-<","\\:<","\\:\\-\\[","\\:\\[", "\\:\\{","\\|\\-\\)","I\\-\\)","\\|\\-O","I\\-O","\\|\\)","I\\)","\\|O","IO","\\(\\-\\_\\-\\)zzz","\\:\\#", "\\:x","\\:\\-\\#","\\=\\#","x\\#","8\\#", "\\:\\-x", "\\=x", "8x","\\:\\'\\(","\\:\\`\\(","\\=\\'\\(","\\=\\`\\(", "x\\'\\(","x\\`\\(","8\\'\\(","8\\`\\(", "\\:\\'\\-\\(", "\\:\\`\\-\\(", "\\:\\'C","\\:\\`C","\\:\\'\\-C","x\\'C","x\\`C","8\\'C","8\\`C","\\=\\'\\C","\\=\\`\\C","\\<3","\\:\\*", "\\:\\^\\*"];
    for (var i = 0; i < find.length; i++) {
      find[i] = new RegExp(find[i], "g");
    }
    this.makeEmo = function(text)
    {
      return text.makeEmo();
    }
  }

var seviEmo = new seviEmoticon('public/img/emoticons');