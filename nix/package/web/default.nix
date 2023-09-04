{
  lib,
  buildNpmPackage,
}:
buildNpmPackage {
  name = "traewelling-web";
  src = lib.cleanSource ../../..;

  npmDepsHash = "sha256-KSwTzmwjETsaWPEWzgVIefE6pwwAiq5UmxEJEDpyU/M=";
  npmPackFlags = ["--ignore-scripts"];
  npmBuildScript = "production";

  prePatch = ''
    # delete public directory to only get web output results in this derivation
    rm -rf public
  '';

  installPhase = ''
    runHook preInstall
    cp -r public $out
    runHook postInstall
  '';
}
