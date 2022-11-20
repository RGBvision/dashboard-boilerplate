DROP TABLE IF EXISTS "public"."thumbnails" CASCADE;
CREATE TABLE "public"."thumbnails" ("size" VARCHAR(50) , "width" INTEGER, "height" INTEGER);
DROP INDEX IF EXISTS "size";
CREATE UNIQUE INDEX "size" ON "public"."thumbnails" ("size");

INSERT INTO "public"."thumbnails"("size", "width", "height") VALUES ('big', 800, 600);
INSERT INTO "public"."thumbnails"("size", "width", "height") VALUES ('med', 640, 480);
INSERT INTO "public"."thumbnails"("size", "width", "height") VALUES ('min', 320, 240);
INSERT INTO "public"."thumbnails"("size", "width", "height") VALUES ('mic', 150, 150);