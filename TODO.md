1. Update the `billing` package so that I have an interface to provide...
   Specifically byrokrat/billing#3 and byrokrat/billing#2.

1. Rename `Sniffer` => `FileTypeGuesser`
   (`SnifferException` => `FileTypeUnknownException`)

1. Remove all dependencies on `ledgr/giro` and `srcOld`

1. Create a `FileTypes` interface with type identifiers.

1. Support billing at next possible date in `Writer` (the low level syntax for
   this is `GENAST`, instead of a numeric date)

1. Generated files must NOT include (end with) and empty line
   In the deprecated georg system this was solved using
   ```php
   return rtrim($this->buildNative(), "\r\n");
   ```
   in `DonorWorker->billAll()`.

1. Write a `FileObject` that extends `SplFileObject` in `Reader`.

1. Implement the `.json` file solution for automating tests on test files. file
   type guessing and parsing could then be tested in an automated manner.

1. Write something like `byrokrat\autogiro\testfiles\FileProvidingTrait` to
   allow for easy access to test files when writing tests.
