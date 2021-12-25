clean:
	# Removes the build directory
	rm -rf build

update:
	# Updates the package.json file
	ppm --generate-package="src/Synical"

build:
	# Compiles the package
	mkdir build
	ppm --compile="src/Synical" --directory="build"

install:
	# Installs the compiled package to the system
	ppm --fix-conflict --no-prompt --install="build/net.intellivoid.synical.ppm" --branch="production"

install_fast:
	# Installs the compiled package to the system
	ppm --fix-conflict --no-prompt --skip-dependencies --install="build/net.intellivoid.synical.ppm" --branch="production"