github:
let
  commenter = github.event.sender;
  allowlist = {
    # See id on https://api.github.com/users/<username>
    "HerrLevin" = 1267894;
    "jeyemwey" = 2796271;
    "MrKrisKrisu" = 4103693;
    "NyCodeGHG" = 37078297;
    "xanderio" = 6298052;
    # The PR creator
    ${github.event.issue.user.login} = github.event.issue.user.id;
  };
  isAllowedUser = builtins.elem commenter.id (builtins.attrValues allowlist);
in
  if isAllowedUser then
    builtins.trace "The user '${commenter.login}' is allowed to run the command. ✅"
    true
  else
    builtins.throw "The user '${commenter.login}' is not allowed to run the command. ❌"