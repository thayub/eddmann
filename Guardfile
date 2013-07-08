guard :concat, :type => 'css', :files => %w[reset styles], :input_dir => 'src/main/webapp/css', :output => 'src/main/webapp/css/styles.min'

guard :sass, :input => 'src/main/sass', :output => 'src/main/webapp/css'

guard :concat, :type => 'js', :files => %w[scripts], :input_dir => 'src/main/webapp/js', :output => 'src/main/webapp/js/scripts.min'

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
    watch(%r[css/.+])
    watch(%r[js/.+])
    watch('src/main/webapp/css/styles.min.css') do |m|
        css = File.read(m[0])
        File.open(m[0], 'w') { |file| file.write(CSSMin.minify(css)) }
    end
    watch('src/main/webapp/js/scripts.min.js') do |m|
        js = File.read(m[0])
        File.open(m[0], 'w') { |file| file.write(JSMin.minify(js)) }
    end
end