guard :concat, :type => 'css', :files => %w[ reset grid styles syntax ], :input_dir => 'public/assets/css', :output => 'public/assets/css/styles.min'

guard :sass, :input => 'public/assets/sass', :output => 'public/assets/css'

guard :concat, :type => 'js', :files => %w[ twitter scripts ], :input_dir => 'public/assets/js', :output => 'public/assets/js/scripts.min'

module ::Guard
    class Refresher < Guard
        def run_all
            refresh
        end

        def run_on_additions(paths)
            refresh
        end

        def run_on_removals(paths)
            refresh
        end

        def refresh
        end
    end
end

require 'cssmin'
require 'jsmin'

guard :refresher do
    watch(%r[public/assets/css.+])
    watch(%r[public/assets/js.+])
    watch('public/assets/css/styles.min.css') do |m|
        css = File.read(m[0])
        File.open(m[0], 'w') { |file| file.write(CSSMin.minify(css)) }
    end
    watch('public/assets/js/scripts.min.js') do |m|
        js = File.read(m[0])
        File.open(m[0], 'w') { |file| file.write(JSMin.minify(js)) }
    end
end